<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\Library\Services\Validation;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\Merchandising\Actions\FeatureVersionAction;
use SkylarkSoft\GoRMG\Merchandising\Actions\OrderFilterFormat;
use SkylarkSoft\GoRMG\Merchandising\Actions\PoColorSizeBreakdownReProcess;
use SkylarkSoft\GoRMG\Merchandising\Actions\Projection\OrderProjectionAction;
use SkylarkSoft\GoRMG\Merchandising\Actions\StyleEntryAction;
use SkylarkSoft\GoRMG\Merchandising\Exports\OrderColorWiseSummaryExport;
use SkylarkSoft\GoRMG\Merchandising\Exports\WorkOrderSheetExport;
use SkylarkSoft\GoRMG\Merchandising\Features;
use SkylarkSoft\GoRMG\Merchandising\Jobs\TnaReportProcess;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PriceQuotation;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Merchandising\Requests\OrderFormRequest;
use SkylarkSoft\GoRMG\Merchandising\Services\ChartService\Consumers\OrderChartService;
use SkylarkSoft\GoRMG\Merchandising\Services\FileUploadRemoveService;
use SkylarkSoft\GoRMG\Merchandising\Services\Order\OrderDeleteAbleCheckingService;
use SkylarkSoft\GoRMG\Merchandising\Services\Order\OrderUpdateNotificationService;
use SkylarkSoft\GoRMG\Merchandising\Services\OrderService;
use SkylarkSoft\GoRMG\Merchandising\Services\ReportSignatureService;
use SkylarkSoft\GoRMG\SystemSettings\Models\BuyingAgentModel;
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;
use SkylarkSoft\GoRMG\SystemSettings\Models\MerchandisingVariableSettings;
use SkylarkSoft\GoRMG\SystemSettings\Models\Team;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Merchandising\Models\OrderAttachment;
use Throwable;


class OrderController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        $search = '';
        $sort = request('sort') ?? 'desc';

        $paginateNumber = request('paginateNumber') ?? 15;
        $searchedOrders = 15;

        $orders = Order::query()
            ->userWiseBuyerFilter()
            ->factoryWiseFilter()
            ->with([
                'attachments',
                'factory:id,factory_name,factory_short_name',
                'buyer:id,name',
                'productCategory:id,category_name',
                'teamLeader',
                'currency',
                'season',
                'dealingMerchant:id,screen_name',
                'factoryMerchant:id,screen_name',
                'productDepartment:id,product_department',
                'purchaseOrders',
                'createdBy'
            ])
            ->when(request('type') == 'Approved Order', function ($query) {
                $query->whereHas('purchaseOrders', function ($q) {
                    return $q->where('ready_to_approved', 1)
                        ->where('is_approved', 1);
                });
            })
            ->when(request('type') == 'Un Approved Order', function ($query) {
                $query->whereHas('purchaseOrders', function ($q) {
                    return $q->where('ready_to_approved', 1)
                        ->where('is_approved', 0);
                });
            })
            ->withCount('purchaseOrders')
            ->withSum('purchaseOrders as total_po_quantity', 'po_quantity')
            ->withSum(['purchaseOrders as total_confirm_quantity' => function ($query) {
                return $query->where('order_status', 'Confirm');
            }], 'po_quantity')
            ->orderBy('id', $sort)->paginate($paginateNumber);

        $chartService = new OrderChartService();

        $dashboardOverview = $chartService->dashboardOverview();

        return view('merchandising::order.index', compact(
            'orders',
            'chartService',
            'search',
            'dashboardOverview',
            'paginateNumber',
            'searchedOrders'));
    }

    /**
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('merchandising::order.create');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getVariableSettings(Request $request): JsonResponse
    {
        try {
            $variable_data = MerchandisingVariableSettings::query()
                ->where("factory_id", $request->query("factory_id"))
                ->where("buyer_id", $request->query("buyer_id"))
                ->first();

            return response()->json($variable_data, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param Request $request
     * @param OrderService $orderService
     * @return JsonResponse
     */
    public function loadData(Request $request, OrderService $orderService): JsonResponse
    {
        $data['regions'] = $orderService->region();
        $data['teams'] = Team::query()
            ->where('status', 'Active')
            ->where('deleted_at', null)
            ->where('role', 'Leader')
            ->with('member')
            ->withoutGlobalScope('factoryId')
            ->filterWithAssociateFactory('teamWiseFactories', $request->get('factoryId'))
            ->get()->map(function ($team) {
                return [
                    'id' => $team->id,
                    'memberId' => $team->member_id,
                    'team_name' => $team->team_name ?? '',
                    'name' => $team->member->first_name . ' ' . $team->member->last_name,
                ];
            });
        $data['currencies'] = Currency::all();
        $data['buyingAgents'] = BuyingAgentModel::query()
            ->where('factory_id', $request->get('factoryId'))
            ->get();
        $data['uom'] = collect(PriceQuotation::STYLE_UOM)->map(function ($value, $key) {
            return [
                "id" => $key,
                "unit_of_measurement" => $value,
            ];
        })->values();
        $data['shipModes'] = $orderService->shipMode();
        $data['packing'] = $orderService->packing();

        return response()->json($data);
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getQuotation(Request $request): JsonResponse
    {
        try {
            $quotations = PriceQuotation::query()
                ->where("factory_id", $request->get('factory_id'))
                ->where("buyer_id", $request->get('buyer_id'))
                ->get();

            return response()->json($quotations, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @return JsonResponse
     */
    public function loadItem(): JsonResponse
    {
        $data = GarmentsItem::all();

        return response()->json($data);
    }

    /**
     * @param OrderFormRequest $request
     * @param StyleEntryAction $styleEntryAction
     * @return JsonResponse
     * @throws Throwable
     */
    public function save(OrderFormRequest $request, StyleEntryAction $styleEntryAction, OrderProjectionAction $orderProjectionAction): JsonResponse
    {
        try {
            DB::beginTransaction();

            $data = $request->except('id', 'job_no', 'smvDetails', 'images', 'file');
            $data['item_details'] = $request->get('smvDetails');

            if ($request->get('editDataId')) {
                unset($data['copy_status']);
                unset($data['order_copy_from']);
                $order = Order::query()->findOrFail($request->get('editDataId'));
                $order->update($data);

                OrderUpdateNotificationService::for($order)->notify();

            } else {
                $order = Order::query()->create($data);
                TnaReportProcess::dispatch($order);
            }
            $styleEntryAction->execute($order, 'order_id', $request);
            if ($request->get('copy_status') == 1 && $request->get('images')) {
                $order->update(['images' => $request->get('images')]);
            } else {
                if ($request->get('images') &&
                    strpos($request->get('images'), 'image') !== false &&
                    strpos($request->get('images'), 'base64') !== false) {
                    $image_path = FileUploadRemoveService::fileUpload('orders', $request->get('images'), 'image');
                    if ($request->get('editDataId') && Storage::disk('public')->exists($order->images)) {
                        Storage::delete($order->images);
                    }
                    $order->update(['images' => $image_path]);
                }
            }

            if ($request->get('files')) {
                $file_path = '';
                foreach ($request->get('files') as $attachment) {
                    if (strpos($attachment['file'], 'pdf') !== false &&
                        strpos($attachment['file'], 'base64') !== false) {

                        $fileType = explode('/', mime_content_type($attachment['file']))[1];
                        $file_path = FileUploadRemoveService::fileUpload('orders', $attachment['file'], 'application');

                        if ($request->get('editDataId') && $order->attachments->count() > 0) {
                            foreach ($order->attachments as $file) {
                                if (Storage::disk('public')->exists($file->path)) {
                                    Storage::delete($file->path);
                                }
                            }
                        }
                        OrderAttachment::create([
                            'order_id' => $order->id,
                            'type' => $fileType,
                            'name' => $attachment['name'],
                            'path' => $file_path,
                        ]);
                    }
                }
                $order->update(['file' => $file_path]);
            }
//            if ($request->get('file') &&
//                strpos($request->get('file'), 'pdf') !== false &&
//                strpos($request->get('file'), 'base64') !== false) {
//                $file_path = FileUploadRemoveService::fileUpload('orders', $request->get('file'), 'application');
//                if ($request->get('editDataId') && Storage::disk('public')->exists($order->file)) {
//                    Storage::delete($order->file);
//                }
//                $order->update(['file' => $file_path]);
//            }

            $orderProjectionAction->attachProjectionPurchaseOrder($order);
            // Unnecessary Garment Item Breakdown Delete From Po ColorSize table
            PoColorSizeBreakdownReProcess::handle($order->id, $request->get('smvDetails')['details']);

            DB::commit();

            return response()->json([
                'status' => 'Success',
                'message' => 'Data Saved Successfully',
                'data' => $order,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'msg' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function fetchPdfAttachments($order_id): JsonResponse
    {
        $attachments = OrderAttachment::query()
            ->where([
                'order_id' => $order_id,
                'type' => 'pdf'
            ])->get();
        return response()->json([
            'status' => 'success',
            'data' => $attachments
        ]);
    }

    /**
     * @throws Throwable
     */
    public function deletePdfAttachment(Request $request): JsonResponse
    {
        $attachment = OrderAttachment::query()->where([
            'id' => $request->get('attachment_id', null),
            'order_id' => $request->get('order_id', null)
        ])->first();

        if ($attachment->exists()) {
            if (Storage::disk('public')->exists($attachment->path)) {
                Storage::delete($attachment->path);
                $attachment->deleteOrFail();
            }
            //clear attachment from order table if deleted attachment matched with stored data.
            Order::query()->where('file', $attachment->path)->update(['file' => '']);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Deleted Successful'
        ]);
    }

    /**
     * @param Request $request
     */
    public function orderImageRemove(Request $request)
    {
        $order = Order::query()->where("id", $request->get('order_id'))->first();
        if ($order->copy_status != 1 && isset($order->images)) {
            $image_name_to_delete = $order->images;
            if (Storage::disk('public')->exists($image_name_to_delete) && $image_name_to_delete) {
                Storage::delete($image_name_to_delete);
            }
        }
        $order->update([
            "images" => null,
        ]);
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function edit(Request $request)
    {
        return view('merchandising::order.create');
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function loadOldData($id): JsonResponse
    {
        $data['order'] = Order::with('priceQuotation', 'styleEntry')->findOrFail($id);
        $data['team_name'] = Team::query()
            ->where('member_id', $data['order']->team_leader_id)
            ->where('role', 'Leader')
            ->first()->team_name ?? '';

        return response()->json($data);
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function search(Request $request, OrderFilterFormat $orderFilterFormat)
    {
        $search = $request->get('search');
        $sort = request('sort') ?? 'desc';
        $paginateNumber = (request('paginateNumber')) ? intval(request('paginateNumber')) : 15;
        $page = request('page');
        $orders = $orderFilterFormat->handle($search, $sort, $page, $paginateNumber);
        $searchedOrders = $orders->total();
        $chartService = new OrderChartService();

        $dashboardOverview = $chartService->dashboardOverview();


        return view('merchandising::order.index', compact(
                'orders',
                'chartService',
                'search',
                'dashboardOverview',
                'paginateNumber',
                'searchedOrders')
        );
    }

    /**
     * @param $id
     * @param FeatureVersionAction $featureVersionAction
     * @return Application|RedirectResponse|Redirector
     * @throws Throwable
     */
    public function delete($id, FeatureVersionAction $featureVersionAction)
    {
        //Delete item if job_no is not exist in budget table
        $validate = Validation::check(Order::find($id)->job_no, [Budget::class, 'job_no']);
        if (!$validate) {
            Session::flash('error', 'You Can Not Delete This Item!');
            return back();
        }

        try {
            DB::beginTransaction();
            $order = Order::query()->find($id);
            $orderDeleteAble = (new OrderDeleteAbleCheckingService($order))->action();
            if ($orderDeleteAble['status']) {
                $order->delete();
                $featureVersionAction->detach(Features::ORDER, $id);
            }

            Session::flash($orderDeleteAble['flash_status'], $orderDeleteAble['msg']);
            DB::commit();
        } catch (Exception $e) {
            Session::flash('error', 'Something Went Wrong');
        }

        return redirect('/orders');
    }

    /**
     * @param Request $request
     * @param $orderId
     * @return JsonResponse
     */
    public function loadPo(Request $request, $orderId): JsonResponse
    {
        $orders = Order::with(["purchaseOrders", "purchaseOrders.poDetails.garmentItem"])
            ->when($request->get('po_no'), function ($query) use ($request) {
                $query->whereHas("purchaseOrders", function ($query) use ($request) {
                    $query->where("po_no", "LIKE", "%{$request->get('po_no')}%");
                });
            })
            ->when($request->get('po_quantity'), function ($query) use ($request) {
                $query->whereHas("purchaseOrders", function ($query) use ($request) {
                    $query->where("po_quantity", $request->get('po_quantity'));
                });
            })
            ->where("id", $orderId)->first();

        return response()->json([
            'status' => 'Success',
            'type' => 'PO Details',
            'data' => $orders,
            "search" => $request->all(),
        ]);
    }


    public function getWorkOrderSheet(Request $request)
    {
        $orderId = $request->order_id;
        $order = Order::query()
            ->findOrFail($orderId);

        $reportData = [
            'id' => $order->id,
            'buyer' => $order->buyer->name,
            'buying_agent' => $order->buyingAgent->buying_agent_name,
            'style_no' => $order->style_name,
            'booking_no' => $order->reference_no,
            'repeat_no' => $order->repeated_style,
            'dealing_merchant' => $order->dealingMerchant->screen_name,
            'team_name' => $order->teamLeader->screen_name,
            'season' => $order->season->season_name,
            'shipment_date' => $order->purchaseOrders->first()['ex_factory_date'] ?? '',
            'order_qty' => $order->pq_qty_sum,
            'total_number_of_po' => $order->purchaseOrders->count(),
            'item' => collect($order->item_details['details'])->implode('item_name', ', ') ?? '',
            'fabric_booking_no' => $order->reference_no,
            'fabrication' => $order->fabric_composition,
            'excess_cutting_percent' => '',
            'remark' => $order->remarks,
        ];

        foreach ($order->purchaseOrders as $key => $purchaseOrder) {

            $qtyMatrix = collect($purchaseOrder->poDetails)->pluck('quantity_matrix')->collapse();
            $actualQty = collect($qtyMatrix)
                ->where('particular', PurchaseOrder::QTY)
                ->groupBy('color_id');

            $excessCUtPercent = collect($qtyMatrix)
                ->where('particular', PurchaseOrder::EX_CUT)
                ->groupBy('color_id');

            $reportData['purchaseOrder'][$purchaseOrder->po_no] = [
                'po_no' => $purchaseOrder->po_no,
                'po_qty' => $purchaseOrder->po_quantity,
                'print' => $purchaseOrder->print_status == 1 ? 'Yes' : 'No',
                'embroidery_status' => $purchaseOrder->embroidery_status == 1 ? 'Yes' : 'No',
                'update_time' => $purchaseOrder->updated_at,
                'shipment_date' => $purchaseOrder->ex_factory_date,
                'po_remarks' => $purchaseOrder->remarks,
                'actual_qty' => $actualQty,
                'ex_cut_percent' => $excessCUtPercent,
            ];
        }

        //return $reportData;


        return view('merchandising::order.work-order-sheet', [
            'order' => $order,
            'reportData' => $reportData
        ]);
    }


    public function workOrderSheetPdf($id)
    {
        $orderId = $id;
        $order = Order::query()
            ->findOrFail($orderId);

        $reportData = [
            'id' => $order->id,
            'buyer' => $order->buyer->name,
            'buying_agent' => $order->buyingAgent->buying_agent_name,
            'style_no' => $order->style_name,
            'booking_no' => $order->reference_no,
            'repeat_no' => $order->repeat_no,
            'dealing_merchant' => $order->dealingMerchant->screen_name,
            'team_name' => $order->teamLeader->screen_name,
            'season' => $order->season->season_name,
            'shipment_date' => $order->purchaseOrders->first()['ex_factory_date'] ?? '',
            'order_qty' => $order->pq_qty_sum,
            'total_number_of_po' => $order->purchaseOrders->count(),
            'item' => collect($order->item_details['details'])->implode('item_name', ', ') ?? '',
            'fabric_booking_no' => $order->reference_no,
            'fabrication' => $order->fabric_composition,
            'excess_cutting_percent' => '',
            'remark' => $order->remarks,
        ];

        foreach ($order->purchaseOrders as $key => $purchaseOrder) {

            $qtyMatrix = collect($purchaseOrder->poDetails)->pluck('quantity_matrix')->collapse();
            $actualQty = collect($qtyMatrix)
                ->where('particular', PurchaseOrder::QTY)
                ->groupBy('color_id');

            $excessCUtPercent = collect($qtyMatrix)
                ->where('particular', PurchaseOrder::EX_CUT)
                ->groupBy('color_id');

            $reportData['purchaseOrder'][$purchaseOrder->po_no] = [
                'po_no' => $purchaseOrder->po_no,
                'po_qty' => $purchaseOrder->po_quantity,
                'print' => $purchaseOrder->print_status == 1 ? 'Yes' : 'No',
                'embroidery_status' => $purchaseOrder->embroidery_status == 1 ? 'Yes' : 'No',
                'update_time' => $purchaseOrder->updated_at,
                'shipment_date' => $purchaseOrder->ex_factory_date,
                'po_remarks' => $purchaseOrder->remarks,
                'actual_qty' => $actualQty,
                'ex_cut_percent' => $excessCUtPercent,
            ];
        }

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('merchandising::order.work-order-sheet-pdf',
                ['reportData' => $reportData])
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer',),
            ]);
        return $pdf->stream('work-order-sheet.pdf');
    }

    public function workOrderSheetExcel($id)
    {
        $orderId = $id;
        $order = Order::query()
            ->findOrFail($orderId);

        $reportData = [
            'id' => $order->id,
            'buyer' => $order->buyer->name,
            'buying_agent' => $order->buyingAgent->buying_agent_name,
            'style_no' => $order->style_name,
            'booking_no' => $order->reference_no,
            'repeat_no' => $order->repeat_no,
            'dealing_merchant' => $order->dealingMerchant->screen_name,
            'team_name' => $order->teamLeader->screen_name,
            'season' => $order->season->season_name,
            'shipment_date' => $order->purchaseOrders->first()['ex_factory_date'] ?? '',
            'order_qty' => $order->pq_qty_sum,
            'total_number_of_po' => $order->purchaseOrders->count(),
            'item' => collect($order->item_details['details'])->implode('item_name', ', ') ?? '',
            'fabric_booking_no' => $order->reference_no,
            'fabrication' => $order->fabric_composition,
            'excess_cutting_percent' => '',
            'remark' => $order->remarks,
        ];

        foreach ($order->purchaseOrders as $key => $purchaseOrder) {

            $qtyMatrix = collect($purchaseOrder->poDetails)->pluck('quantity_matrix')->collapse();
            $actualQty = collect($qtyMatrix)
                ->where('particular', PurchaseOrder::QTY)
                ->groupBy('color_id');

            $excessCUtPercent = collect($qtyMatrix)
                ->where('particular', PurchaseOrder::EX_CUT)
                ->groupBy('color_id');

            $reportData['purchaseOrder'][$purchaseOrder->po_no] = [
                'po_no' => $purchaseOrder->po_no,
                'po_qty' => $purchaseOrder->po_quantity,
                'print' => $purchaseOrder->print_status == 1 ? 'Yes' : 'No',
                'embroidery_status' => $purchaseOrder->embroidery_status == 1 ? 'Yes' : 'No',
                'update_time' => $purchaseOrder->updated_at,
                'shipment_date' => $purchaseOrder->ex_factory_date,
                'po_remarks' => $purchaseOrder->remarks,
                'actual_qty' => $actualQty,
                'ex_cut_percent' => $excessCUtPercent,
            ];
        }

        return Excel::download(new WorkOrderSheetExport($reportData), 'work-order-sheet.xlsx');
    }

    public function colorWiseSummary(Order $order)
    {
        $order->load([
            'buyer',
            'buyingAgent',
            'dealingMerchant',
            'season',
            'purchaseOrders',
            'purchaseOrderDetails',
            'purchaseOrderDetails.size',
            'purchaseOrderDetails.color',
            'purchaseOrderDetails.purchaseOrder'
        ]);
        $data = $this->formatColorWiseSummaryView($order);
        return view('merchandising::order.color_wise_summary.view', $data);
    }

    public function colorWiseSummaryPdf(Order $order)
    {
        $order->load([
            'buyer',
            'buyingAgent',
            'dealingMerchant',
            'season',
            'purchaseOrders',
            'purchaseOrderDetails',
            'purchaseOrderDetails.size',
            'purchaseOrderDetails.color',
            'purchaseOrderDetails.purchaseOrder'
        ]);
        $data = $this->formatColorWiseSummaryView($order);
        $data['signature'] = ReportSignatureService::getApprovalSignature(Order::class, $order->id);
        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('merchandising::order.color_wise_summary.pdf', $data)
            ->setPaper('a4')->setOrientation('landscape')
            ->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);
        return $pdf->stream('color_wise_summary.pdf');
    }

    public function colorWiseSummaryExcel(Order $order): BinaryFileResponse
    {
        $order->load([
            'buyer',
            'buyingAgent',
            'dealingMerchant',
            'season',
            'purchaseOrders',
            'purchaseOrderDetails',
            'purchaseOrderDetails.size',
            'purchaseOrderDetails.color',
            'purchaseOrderDetails.purchaseOrder'
        ]);
        $data = $this->formatColorWiseSummaryView($order);
        return Excel::download(new OrderColorWiseSummaryExport($data), 'color-wise-summary-report.xlsx');
    }

    public function formatColorWiseSummaryView($order): array
    {
        $shipmentDate = $order->purchaseOrders->sortByDesc('ex_factory_date')->first()['ex_factory_date'] ?? null;
        $shipmentDate = $shipmentDate ? Carbon::make($shipmentDate)->format('d/m/Y') : "";

        $data = [
            'id' => $order->id,
            'buyer' => $order->buyer->name,
            'shipment_date' => $shipmentDate,
            'buying_agent' => $order->buyingAgent->buying_agent_name,
            'order_qty' => $order->pq_qty_sum,
            'style_name' => $order->style_name,
            'total_po_no' => $order->purchaseOrders->count(),
            'booking_no' => $order->reference_no,
            'item' => collect($order['item_details']['details'])->pluck('item_name')->implode(', '),
            'repeat_no' => $order->repeat_no,
            'fabric_booking_no' => $order->job_no,
            'dealing_merchant' => $order->dealingMerchant->screen_name,
            'fabrication' => $order->fabrication,
            'team_name' => $order->teamLeader->screen_name,
            'ex_cut_percent' => $order->purchaseOrderDetails->first()['excess_cut_percent'] ?? null,
            'season' => $order->season->season_name,
            'remarks' => $order->remarks
        ];
        $data['sizes'] = $sizes = $order->purchaseOrderDetails->unique('size_id')->pluck('size');

        $data['color_wise_po'] = $order->purchaseOrderDetails
            ->sortBy('color_id')
            ->groupBy(['color_id', 'purchase_order_id'])
            ->map(function ($collection) use ($sizes) {
                return $collection->map(function ($item) use ($sizes) {
                    $colorWiseData = [
                        'color' => $item->first()['color']['name'],
                        'po_no' => $item->first()->purchaseOrder['po_no'],
                        'total_qty' => $item->sum('quantity')
                    ];
                    foreach ($sizes as $size) {
                        $colorWiseData[$size->name] = [
                            'size_id' => $size->id,
                            'qty' => $item->where('size_id', $size->id)->sum('quantity'),
                        ];
                    }
                    return $colorWiseData;
                });
            });

        return $data;

    }

    public function checkItemInBundleCard($orderId, $itemId): JsonResponse
    {
        $bundleCard = BundleCard::query()
            ->where('order_id', $orderId)
            ->where('garments_item_id', $itemId)
            ->first();

        return response()->json([
            'message' => 'Fetch Item status',
            'data' => isset($bundleCard),
        ], Response::HTTP_OK);
    }
}
