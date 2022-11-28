<?php


namespace SkylarkSoft\GoRMG\Commercial\Controllers\BTBMarginLC;

use DB;
use PDF;
use Closure;
use Throwable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Facades\MailChannelFacade;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Constants\ApplicationConstant;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;
use App\MailChannels\Mailers\Commercial\BtbLcMail;
use SkylarkSoft\GoRMG\Commercial\Models\B2BMarginLC;
use SkylarkSoft\GoRMG\Commercial\Models\ProformaInvoice;
use SkylarkSoft\GoRMG\Commercial\Models\B2BMarginLCDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\CommercialSetting;
use SkylarkSoft\GoRMG\Commercial\Requests\B2BMarginLcFormRequest;

class B2BMarginLCController extends Controller
{
    public function index()
    {
        $b2bData = B2BMarginLC::query()->withSum('details as lc_sc_value', 'lc_sc_value')->with([
            'lienBank',
            'item',
            'supplier',
        ])->latest()->paginate();

        return view('commercial::BTB-Margin-Lc.index', [
            'b2bData' => $b2bData,
        ]);
    }

    public function create()
    {
        return view('commercial::BTB-Margin-Lc.create_update');
    }

    public function view($id)
    {
        $b2bData = B2BMarginLC::query()->with([
            'lienBank',
            'item',
            'supplier',
            'details',
            'factory',
            'currency',
            'details.salesContract',
            'details.exportLC',
            'unitOfMeasurement'
        ])->where('id', $id)->get();
        $exportLc = collect($b2bData)->first()->details ? collect($b2bData)->first()->details->map(function ($collection) {
            return [
                'lc_no_date' => $collection->salesContract ?
                    ('EXPORT L/C NO. ' . $collection->salesContract->contract_number . ' DATE: '
                        . (isset($collection->salesContract->contract_date) ? Carbon::make($collection->salesContract->contract_date)->format('d.m.Y') : null)) :
                    ('EXPORT L/C NO. ' . $collection->exportLC->lc_number . ' DATE: '
                        . (isset($collection->exportLC->lc_date) ? Carbon::make($collection->exportLC->lc_date)->format('d.m.Y') : null)),
                'bank_file_no' => $collection->salesContract ?
                    $collection->salesContract->bank_file_no :
                    $collection->exportLC->bank_file_no
            ];
        }) : '';
        $proformaInvoice = ProformaInvoice::query()->with('item')->whereIn('id', [collect($b2bData)->first()->pi_ids])->get();
        $b2bData = (collect($b2bData)->toArray())[0] ?? [];
        $b2bData['proformaInvoice'] = $proformaInvoice->map(function ($item) {
            return "PROFORMA INVOICE NO: " . $item->pi_no . " DATE " . ($item->pi_receive_date ? Carbon::make($item->pi_receive_date)->format('d.m.Y') : '') . "// HS CODE " . $item->hs_code . " /" . $item->item->name;
        })->implode(', ');
        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView("commercial::BTB-Margin-Lc.view", compact('b2bData','exportLc'))
            ->setPaper('legal');

        return $pdf->stream("B2B_Margin_LC_of_".$b2bData['lc_number'].".pdf");
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function proformaInvoiceSearch(Request $request): JsonResponse
    {
        try {
            $data = ProformaInvoice::query()
                ->with([
                    'item',
                    'importer',
                    'supplier',
                ])
                ->where('importer_id', $request->get('importer_id'))
                ->where('supplier_id', $request->get('supplier_id'))
                ->where('item_category', $request->get('item_id'))
                ->when($request->get('pi_no'), function ($query) use ($request) {
                    $query->where('pi_no', $request->get('pi_no'));
                })->get()->map(Closure::fromCallable([$this, 'format']));

            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(B2BMarginLcFormRequest $request, B2BMarginLC $b2BMarginLC): JsonResponse
    {
        try {
            $b2BMarginLC->fill($request->all())->save();

            if(!empty($request->get('pi_ids'))){
                ProformaInvoice::whereIn('id', $request->get('pi_ids'))->update(['b_to_b_margin_lc_id'=> $b2BMarginLC->id]);
            }

            // Send mail to Team leader
            // $settings = CommercialSetting::first();
            // if(($settings->count() > 0) && (@$settings->mailing == '1')){
            //     MailChannelFacade::for(new BtbLcMail($b2BMarginLC->id, $settings->teamleader_id));
            // }

            return response()->json(['message' => ApplicationConstant::S_STORED, 'b2bMarginLc' => $b2BMarginLC]);
        } catch (\Exception $e) {
            return response()->json(['message' => ApplicationConstant::SOMETHING_WENT_WRONG, 'errMsg' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function update(B2BMarginLC $b2BMarginLC, B2BMarginLcFormRequest $request): JsonResponse
    {
        try {
            $b2BMarginLC->update($request->all());
            // return $b2BMarginLC;

            if(!empty($request->get('pi_ids'))){
                ProformaInvoice::whereIn('id', $request->get('pi_ids'))->update(['b_to_b_margin_lc_id'=> $request->get('id')]);
            }
            

            return response()->json(['message' => ApplicationConstant::S_UPDATED, 'b2bMarginLc' => $b2BMarginLC]);
        } catch (\Exception $e) {
            return response()->json(['message' => ApplicationConstant::SOMETHING_WENT_WRONG, 'errMsg' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function show(B2BMarginLC $b2BMarginLC): JsonResponse
    {
        return response()->json($b2BMarginLC);
    }

    public function getDetails(B2BMarginLC $b2BMarginLC): JsonResponse
    {
        try {
            $data = $b2BMarginLC->load('details');
            // $data = $b2BMarginLC->load('details.buyer');
          

            foreach ($data->details as $key => $detail) {
                $export_lc_id = $detail->export_lc_id;
                $sales_contract_id = $detail->sales_contract_id;
                $lc_sc_value = $detail->lc_sc_value;
                $lc_sc_type = $export_lc_id ? 'LC' : ($sales_contract_id ? 'SC' : null);
                $current_distribution = ($detail->current_distribution=='NaN')?0:$detail->current_distribution;
                
                $cumulative_distribution = $export_lc_id ? B2BMarginLCDetail::where('export_lc_id', $export_lc_id)
                    ->sum('current_distribution') : ($sales_contract_id ?
                    B2BMarginLCDetail::where('sales_contract_id', $sales_contract_id)
                        ->sum('current_distribution') : 0);
                        
                $occupied_percentage = $lc_sc_value > 0 ? ($cumulative_distribution * 100 / $lc_sc_value) : 0;
                $max_current_distribution = ($lc_sc_value + $current_distribution) - $cumulative_distribution;
                $buyerName = collect($detail->buyer_names)->implode('name',',');

                $data['details'][$key]['lc_sc_type'] = $lc_sc_type;
                $data['details'][$key]['old_current_distribution'] = $current_distribution;
                $data['details'][$key]['cumulative_distribution'] = $cumulative_distribution;
                $data['details'][$key]['occupied_percentage'] = $occupied_percentage;
                $data['details'][$key]['cumulative_distribution_calculation'] = $cumulative_distribution;
                $data['details'][$key]['max_current_distribution'] = $max_current_distribution;
                $data['details'][$key]['buyer'] = $buyerName;

            }

            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param B2BMarginLC $b2BMarginLC
     * @param Request $request
     * @return JsonResponse
     */
    public function details(B2BMarginLC $b2BMarginLC, Request $request): JsonResponse
    {
        $request->validate(['current_distribution.*' => 'required']);

        try {
            foreach ($request->all() as $details) {
                if ($id = $details['id'] ?? null) {
                    $b2BMarginLC->details()->find($id)->update($details);
                } else {
                    $b2BMarginLC->details()->create($details);
                }
            }
            //$b2BMarginLC->details()->createMany($request->all());
            return response()->json($b2BMarginLC, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function detailsDelete(B2BMarginLCDetail $b2BMarginLCDetail): JsonResponse
    {
        try {
            $b2BMarginLCDetail->delete();

            return response()->json(['message' => 'Details Deleted Successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param B2BMarginLC $b2BMarginLC
     * @return RedirectResponse
     * @throws Throwable
     */
    public function delete(B2BMarginLC $b2BMarginLC): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $b2BMarginLC->details()->delete();
            $b2BMarginLC->delete();
            DB::commit();

            Session::flash('success', 'Data Deleted successfully!');

            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('error', "Something went wrong!{$e->getMessage()}");

            return redirect()->back();
        }
    }

    public function format($collection): array
    {
        return [
            'id' => $collection->id,
            'pi_no' => $collection->pi_no,
            'pi_date' => $collection->pi_receive_date,
            'item_category_id' => $collection->item_category,
            'item_category' => $collection->item->item_name,
            'importer_id' => $collection->importer_id,
            'importer' => $collection->importer->factory_name,
            'supplier_id' => $collection->supplier_id,
            'supplier' => $collection->supplier->name,
            'last_ship_date' => $collection->last_shipment_date,
            'hs_code' => $collection->hs_code,
            'pi_basis' => $collection->pi_basis,
            'currency' => $collection->currency,
            'pi_basis_value' => $collection->pi_basis == ApplicationConstant::INDEPENDENT_BASIS ? 'Independent' : 'Work Order Based',
            'pi_value' => $collection->details ? $collection->details->net_total : 0.00,
        ];
    }
}
