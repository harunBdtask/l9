<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\Merchandising\Actions\POFileLogAction;
use SkylarkSoft\GoRMG\Merchandising\Actions\POReplaceFromFileAction;
use SkylarkSoft\GoRMG\Merchandising\Actions\StyleEntry\PurchaseOrderGenerateAction;
use SkylarkSoft\GoRMG\Merchandising\Exports\PO\PoExcelSampleFile;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\POFileModel;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Merchandising\Requests\POExcelFileFormRequest;
use SkylarkSoft\GoRMG\Merchandising\Services\PoFile\CapacityPlanService;
use SkylarkSoft\GoRMG\Merchandising\Services\PoFile\PoExcelFileConverter;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class POFilesExcelController extends Controller
{
    const PO_FILES = "/po_files/";

    public function index(Request $request, CapacityPlanService $capacityPlanService)
    {
        $poFiles = POFileModel::query()
            ->with('buyer')
            ->when($request->query('search'), function ($query) use ($request) {
                $query->where("po_no", "LIKE", "%{$request->query('search')}%")
                    ->orWhere("created_at", "LIKE", "%{$request->query('search')}%")
                    ->orWhere("style", "LIKE", "%{$request->query('search')}%");
            })->orderBy("id", "DESC");
        // $poGroupByFiles = (clone $poFiles)->groupBy('file')->paginate(10);
        $poGroupByFiles = (clone $poFiles)->paginate(10);

        $factories = Factory::query()->get();
        $buyers = Buyer::query()->where("pdf_conversion_key", "!=", null)->get();
        $styles = Order::query()->get(['style_name as id', 'style_name as text']);

        $fromMonth = $request->get('from_month');
        $toMonth = $request->get('to_month');
        $currentMonth = date('m');
        $afterThreeMonth = Carbon::now()->addMonths(3)->format('m');
        $capacityPlans = $capacityPlanService->getCapacityPlan($fromMonth, $toMonth, $currentMonth, $afterThreeMonth);
        $months = $capacityPlanService->months();
        $totalFiles = POFileModel::all()->count();

        $dashboardOverview = [
            "Total Files" => $totalFiles
        ];

        return view('merchandising::po-files-excel.index', [
            'factories' => $factories,
            'buyers' => $buyers,
            'po_files' => $poFiles->get(),
            'po_group_by_files' => $poGroupByFiles,
            'styles' => $styles,
            'capacityPlans' => $capacityPlans,
            'months' => $months,
            'currentMonth' => ltrim($currentMonth, 0),
            'afterThreeMonth' => ltrim($afterThreeMonth, 0),
            'dashboardOverview' => $dashboardOverview
        ]);
    }

    /**
     * @throws Throwable
     */
    public function store(POExcelFileFormRequest $request, PoExcelFileConverter $fileConverter)
    {
        //dd(Carbon::parse((Date::excelToDateTimeObject('15-8-2022')))->toDateString());
        try {
            DB::beginTransaction();
            $buyerCode = Buyer::query()->where("id", $request->get("buyer_id"))->first();

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $path = $file->getClientOriginalName();
                $file->storeAs('po_files', $path);
                $filePath = 'po_files/' . $path;
                $convertedData = $fileConverter->convert($path);
                $attributes = [
                    'buyer_id' => $request->input('buyer_id'),
                    'buyer_code' => $buyerCode->pdf_conversion_key,
                    // 'style' => $request->get('style'),
                    'file' => $filePath,
                    'processed' => 1,
                ];
                $poWiseGroup = $convertedData->getGroupedColumns($attributes);
                POFileModel::query()->insert($poWiseGroup);

            }

            if (isset($poWiseGroup) && count($poWiseGroup) > 0) {
                $itemStyles = collect($poWiseGroup)->pluck('style')->toArray();
                $orderTotalBalances = Order::query()->whereIn('style_name', $itemStyles)->select('id', 'style_name', 'projection_qty')->get()->keyBy('style_name')->toArray();
                $styleIds = collect($orderTotalBalances)->pluck('id')->toArray();
                $confirmedPosQuantity = PurchaseOrder::selectRaw("po_quantity,order_id,order_status, sum(po_quantity) as total_po")
                    ->whereIn('order_id', $styleIds)
                    ->where('order_status', 'Confirm')
                    ->groupBy('order_id')
                    ->get()
                    ->keyBy('order_id')
                    ->toArray();
                $orderFinalBalance = collect($orderTotalBalances)->map(function ($c) use ($confirmedPosQuantity) {
                    $itemPoQuantity = collect($confirmedPosQuantity)->get($c['id']);
                    $projectionQuantity = $c['projection_qty'] == null ? 0 : $c['projection_qty'];
                    $totalPo = $itemPoQuantity['total_po'] ?? 0;
                    $finalBalance = $projectionQuantity - $totalPo;
                    return [
                        'final_balance' => $finalBalance,
                    ];
                })->toArray();
                foreach ($poWiseGroup as $item) {
                    $value = $orderFinalBalance[trim($item['style'])] ?? null;
                    $finalBalance = $value['final_balance'] ?? 0;
                    $order = Order::query()->where('style_name', $item['style'])->first();
                    if (!isset($order)) {
                        continue;
                    }
                    if (! $value || ($order->order_status_id == Order::PROJECTION && ! ($finalBalance >= $item['po_quantity']))) {
                        continue;
                    }
                    (new PurchaseOrderGenerateAction())->execute($order, $finalBalance);
                    $updatedValue = $finalBalance - $item['po_quantity'];
                    $orderFinalBalance[trim($item['style'])]['final_balance'] = $updatedValue;
                }
            }
            DB::commit();
            Session::flash('alert-success', 'Data stored successfully!');
        } catch (Exception $exception) {
            Session::flash('alert-danger', "File was not processed successfully. Please update file content! {$exception->getMessage()}");
        } finally {
            return back();
        }
    }

    public function edit(POFileModel $pOFileModel)
    {
        $quantityMatrix = collect($pOFileModel->quantity_matrix)->map(function ($collection) {
            if ($collection['particulars'] === "Qty.") {
                return $collection;
            }

            return null;
        })->filter(function ($value) {
            return $value !== null;
        })->values();

        return view('merchandising::po-files-excel.form', [
            'pOFileModel' => $pOFileModel,
            'quantityMatrix' => $quantityMatrix,
        ]);
    }

    public function update(Request $request, POFileModel $pOFileModel)
    {
        try {
            $matrix = [];
            foreach ($request->input('po_no') as $key => $poNo) {
                $matrix[] = [
                    "item" => $request->input('item')[$key],
                    "size" => $request->input('size')[$key],
                    "color" => $request->input('color')[$key],
                    "po_no" => $poNo,
                    "value" => $request->input('value')[$key],
                    "league" => $request->input('league')[$key],
                    "item_id" => $request->input('item_id')[$key],
                    "remarks" => $request->input('remarks')[$key],
                    "size_id" => $request->input('size_id')[$key],
                    "color_id" => $request->input('color_id')[$key],
                    "customer" => $request->input('customer')[$key],
                    "fob_price" => $request->input('fob_price')[$key],
                    "particulars" => $request->input('particulars')[$key],
                    "x_factory_date" => $request->input('x_factory_date')[$key],
                    "po_received_date" => $request->input('po_received_date')[$key],
                    'country_id' => $request->input('country_id')[$key],
                    'country_code' => $request->input('country_code')[$key],
                ];

                $matrix[] = [
                    "item" => $request->input('item')[$key],
                    "size" => $request->input('size')[$key],
                    "color" => $request->input('color')[$key],
                    "po_no" => $poNo,
                    "value" => null,
                    "league" => $request->input('league')[$key],
                    "item_id" => $request->input('item_id')[$key],
                    "remarks" => $request->input('remarks')[$key],
                    "size_id" => $request->input('size_id')[$key],
                    "color_id" => $request->input('color_id')[$key],
                    "customer" => $request->input('customer')[$key],
                    "fob_price" => $request->input('fob_price')[$key],
                    "particulars" => 'Rate',
                    "x_factory_date" => $request->input('x_factory_date')[$key],
                    "po_received_date" => $request->input('po_received_date')[$key],
                    'country_id' => $request->input('country_id')[$key],
                    'country_code' => $request->input('country_code')[$key],
                ];

                $matrix[] = [
                    "item" => $request->input('item')[$key],
                    "size" => $request->input('size')[$key],
                    "color" => $request->input('color')[$key],
                    "po_no" => $poNo,
                    "value" => null,
                    "league" => $request->input('league')[$key],
                    "item_id" => $request->input('item_id')[$key],
                    "remarks" => $request->input('remarks')[$key],
                    "size_id" => $request->input('size_id')[$key],
                    "color_id" => $request->input('color_id')[$key],
                    "customer" => $request->input('customer')[$key],
                    "fob_price" => $request->input('fob_price')[$key],
                    "particulars" => 'Ex. Cut %',
                    "x_factory_date" => $request->input('x_factory_date')[$key],
                    "po_received_date" => $request->input('po_received_date')[$key],
                    'country_id' => $request->input('country_id')[$key],
                    'country_code' => $request->input('country_code')[$key],
                ];

                $matrix[] = [
                    "item" => $request->input('item')[$key],
                    "size" => $request->input('size')[$key],
                    "color" => $request->input('color')[$key],
                    "po_no" => $poNo,
                    "value" => null,
                    "league" => $request->input('league')[$key],
                    "item_id" => $request->input('item_id')[$key],
                    "remarks" => $request->input('remarks')[$key],
                    "size_id" => $request->input('size_id')[$key],
                    "color_id" => $request->input('color_id')[$key],
                    "customer" => $request->input('customer')[$key],
                    "fob_price" => $request->input('fob_price')[$key],
                    "particulars" => 'Plan Cut Qty.',
                    "x_factory_date" => $request->input('x_factory_date')[$key],
                    "po_received_date" => $request->input('po_received_date')[$key],
                    'country_id' => $request->input('country_id')[$key],
                    'country_code' => $request->input('country_code')[$key],
                ];

                $matrix[] = [
                    "item" => $request->input('item')[$key],
                    "size" => $request->input('size')[$key],
                    "color" => $request->input('color')[$key],
                    "po_no" => $poNo,
                    "value" => null,
                    "league" => $request->input('league')[$key],
                    "item_id" => $request->input('item_id')[$key],
                    "remarks" => $request->input('remarks')[$key],
                    "size_id" => $request->input('size_id')[$key],
                    "color_id" => $request->input('color_id')[$key],
                    "customer" => $request->input('customer')[$key],
                    "fob_price" => $request->input('fob_price')[$key],
                    "particulars" => 'Article No.',
                    "x_factory_date" => $request->input('x_factory_date')[$key],
                    "po_received_date" => $request->input('po_received_date')[$key],
                    'country_id' => $request->input('country_id')[$key],
                    'country_code' => $request->input('country_code')[$key],
                ];
            }

            $pOFileModel->update([
                'quantity_matrix' => $matrix,
                'po_quantity' => collect($matrix)->sum('value'),
            ]);
            Session::flash('alert-success', 'Data stored successfully!');

            return redirect('/po-files-excel');
        } catch (Exception $exception) {
            Session::flash('alert-danger', "Something went wrong!");
            return back();
        }
    }


    public function download($id)
    {
        $poFile = POFileModel::query()->where("id", $id)->first();

        if (isset($poFile->file)) {
            $fileName = $poFile->file;

            if (Storage::disk('public')->exists($fileName)) {
                return Storage::disk('public')->download($fileName);
            } else {
                Session::flash('alert-danger', "File not found");
            }
        }

        return back();
    }

    public function destroy($id): RedirectResponse
    {
        try {
            $poFile = POFileModel::query()->where("id", $id)->first();
            if (isset($poFile->file)) {
                $fileName = $poFile->file;
                if (Storage::disk('public')->exists($fileName) && $fileName) {
                    Storage::delete($fileName);
                }
            }

            $poFile->delete();
            Session::flash('alert-success', 'File Deleted successfully!');
        } catch (Exception $exception) {
            Session::flash('alert-danger', "Something went wrong!");
        } finally {
            return back();
        }
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function sampleDownload(): BinaryFileResponse
    {
        return Excel::download(new PoExcelSampleFile(), time() . '_sample_po.xlsx');
    }

    /**
     * @throws Throwable
     */
    public function replace($id)
    {
        $POFileModel = POFileModel::query()->with('purchaseOrder')->find($id);
        $bundleCardPo = BundleCard::query()
            ->where('purchase_order_id', $POFileModel->purchaseOrder->id)
            ->first();

        if ($bundleCardPo) {
            return view('merchandising::po-files-excel.cut-po-update', compact('POFileModel'));
        }
        try {
            DB::beginTransaction();
            POReplaceFromFileAction::replace($POFileModel);
            DB::commit();
            Session::flash('alert-success', 'Replacement successfully!');
        } catch (\Exception $exception) {
            Session::flash('alert-danger', 'Something went wrong!');
        } finally {
            return back();
        }
    }

    /**
     * @throws Throwable
     */
    public function storeRemarks(Request $request, $id): RedirectResponse
    {
        $request->validate(['remarks' => 'required']);

        try {
            DB::beginTransaction();
            $remarks = $request->get('remarks');
            $POFileModel = POFileModel::query()->find($id);
            POReplaceFromFileAction::replace($POFileModel);
            POFileLogAction::handle($POFileModel, $remarks);
            $POFileModel->update([
                'status' => 1
            ]);
            DB::commit();
            Session::flash('alert-success', 'Replacement successfully!');
        } catch (Exception $exception) {
            Session::flash('alert-danger', 'Something went wrong!');
        }

        return redirect('/po-files-excel');
    }
}
