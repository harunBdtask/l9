<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API;

use PDF;
use Excel;
use SkylarkSoft\GoRMG\Merchandising\Services\Budget\BudgetService;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Constants\ApplicationConstant;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Inventory\Requests\FabricReceiveRequest;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricReceive;
use SkylarkSoft\GoRMG\Inventory\Services\PISearchForFabricReceive;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBooking;
use SkylarkSoft\GoRMG\Inventory\Services\FabricStockSummaryService;
use SkylarkSoft\GoRMG\Inventory\Services\PIDetailsForFabricReceive;
use SkylarkSoft\GoRMG\Inventory\Exceptions\InvalidBookingNoException;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricReceiveDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\FabricStoreVariableSetting;
use SkylarkSoft\GoRMG\Inventory\Services\BookingDetailsForFabricReceive;
use SkylarkSoft\GoRMG\Inventory\Services\IndependentSearchForFabricReceive;
use SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings\ShortFabricBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBookingDetailsBreakdown;
use SkylarkSoft\GoRMG\Inventory\Exports\FabricReceiveViewExport;

class FabricReceiveController extends Controller
{
    public $status = Response::HTTP_OK;

    public function index(FabricReceive $receive,Request $request)
    {
//        dd($request->all());
        $search = $request->get('search');
        $receive = $receive->with('details')->search($search)->latest()->paginate(15);
        $variableSettings = FabricStoreVariableSetting::query()->first();

        return view('inventory::fabrics.pages.fabric_receive_index', [
            'receive' => $receive,
            'variableSettings' => $variableSettings,
        ]);
    }

    public function create()
    {
        return view('inventory::fabrics.receives');
    }

    /**
     * @throws InvalidBookingNoException
     */
    public function searchFabricFromPIorBooking(Request $request): JsonResponse
    {
        $data = [];

        if ($request->get('search_by') == 'booking_basis') {

            $request->validate([
                'booking_no' => 'required'
            ]);

            $abbr = explode('-', $request->booking_no)[1] ?? null;

            if (!$abbr && !in_array($abbr, ['FB', 'SFB'])) {
                throw new InvalidBookingNoException();
            }

            if ($abbr == 'FB') {
                $data = FabricBooking::with(['detailsBreakdown.budget', 'buyer'])
                    ->where('unique_id', $request->booking_no)
                    ->get()
                    ->map(function ($booking) {
                        return array_merge(
                            ['receivable_type' => 'fabric-booking', 'receivable_id' => $booking->id,],
                            $this->formatBookingSearchData($booking)
                        );
                    });
            }

            if ($abbr == 'SFB') {
                $data = ShortFabricBooking::with(['details', 'buyer'])
                    ->where('unique_id', $request->booking_no)
                    ->get()
                    ->map(function ($booking) {
                        return array_merge(
                            ['receivable_type' => 'short-fabric-booking', 'receivable_id' => $booking->id,],
                            $this->formatBookingSearchData($booking)
                        );
                    });
            }

            return response()->json($data);
        }

        if ($request->get('search_by') == 'pi_basis') {
            $data = (new PISearchForFabricReceive())->getData($request);

            return response()->json($data);
        }

        if ($request->get('search_by') == 'independent') {
            $request->validate([
                'buyer_id' => 'required',
            ]);

            $this->response = (new IndependentSearchForFabricReceive())->getData($request);
        }

        return response()->json($this->response);
    }

    private function formatBookingSearchData($booking): array
    {
        $details = $booking->detailsBreakdown()->with('budget:id,job_no,style_name')->get();
        $poNo = collect($details)->map(function ($detail) {
            return [
                'po_no' => explode(',', $detail->po_no)
            ];
        })->pluck('po_no')
            ->flatten()
            ->unique();
        $poQuery = PurchaseOrder::whereIn('po_no', $poNo);

        $poQuantity = $poQuery->sum('po_quantity');

        $shipmentDate = $poQuery->latest('ex_factory_date')->first()->ex_factory_date ?? '';

        return [
            'buyer' => $booking->buyer->name,
            'buyer_id' => $booking->buyer_id,
            'booking_no' => $booking->unique_id,
            'booking_date' => $booking->booking_date,
            'delivery_date' => $booking->delivery_date,
            'comma_separated_style_name' => collect($details)->pluck('budget.style_name')->unique()->values(),
            'style_name' => collect($details)->pluck('budget.style_name')->unique()->values()->implode(', '),
            'uniq_id_comma_separated' => collect($details)->pluck('budget.job_no')->unique()->values(),
            'uniq_id' => collect($details)->pluck('budget.job_no')->unique()->values()->implode(', '),
            'order_no' => $poNo->implode(', '),
            'order_no_comma_separated' => $poNo->implode(', '),
            'order_qty' => $poQuantity,
            'shipment_date' => $shipmentDate,
            'item_category' => collect($details)->pluck('item_name')->unique()->implode(', '),
            'file_name' => $booking->file_no,
            'reference' => $booking->internal_ref_no,
        ];
    }

    public function getFabricReceiveItemDetails(Request $request): JsonResponse
    {

        switch (\request('receivable_type')) {
            case 'proforma-invoice':
                $this->response = (new PIDetailsForFabricReceive())->getData($request);
                break;

            case 'fabric-booking':
            case 'short-fabric-booking':
                $this->response = (new BookingDetailsForFabricReceive())->getData($request);
                break;

            default:
                break;
        }

        return response()->json($this->response);
    }

    /**
     * @throws Throwable
     */
    public function store(FabricReceiveRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $receiveId = $request->input('id');
            $receive = FabricReceive::query()->findOrNew($receiveId);
            $receive->fill($request->except(['details']))->save();
            if ($request->get('details')) {
                foreach ($request->details as $detail) {
                    $detail['store_id'] = $receive->store_id;
                    $detail['receive_date'] = $receive->receive_date;
                    $receiveDetail = $receive->details()->findOrNew($detail['id'] ?? null);
                    $receiveDetail->fill($detail)->save();
                }
            }
            DB::commit();
            $receive->load('details');
            $this->response['data'] = $this->format($receive);
            $this->response['message'] = ApplicationConstant::S_STORED;
            $this->status = Response::HTTP_CREATED;
        } catch (\Exception $exception) {
            DB::rollBack();
            $this->response['message'] = ApplicationConstant::SOMETHING_WENT_WRONG;
            $this->response['errMsg'] = $exception->getMessage();
            $this->status = Response::HTTP_BAD_REQUEST;
        }

        return response()->json($this->response, $this->status);

    }

    public function show(FabricReceive $receive): array
    {
        $receive->load('details');

        return $this->format($receive);
    }

    public function showDetails($details): JsonResponse
    {
        $details = FabricReceiveDetail::with('body')->where('receive_id', $details)->get();

        return response()->json($this->formatDetails($details));
    }

    /**
     * @throws Throwable
     */
    public function delete(FabricReceive $fabricReceive)
    {
        try {
            DB::beginTransaction();
            $fabricReceive->details()->delete();
            if (count($fabricReceive->barcodeDetails)) {
                $fabricReceive->barcodeDetails()->delete();
            }
            $fabricReceive->delete();
            DB::commit();

            Session::flash('success', 'Data deleted successfully!');
        } catch (Throwable $exception) {
            Session::flash('error', "Something went wrong! {$exception->getMessage()}");
        }
    }

    /**
     * @throws Throwable
     */
    public function detailUpdate(FabricReceiveRequest $request, FabricReceiveDetail $receiveDetail): JsonResponse
    {
        try {
            DB::beginTransaction();
//            $detail = $receive->details()->findOrFail($request->get('details')[0]['id']);
            $receiveDetail->fill($request->get('details')[0])->save();
            DB::commit();

            $this->response['message'] = ApplicationConstant::S_CREATED;
            $this->response['data'] = $receiveDetail;
            $this->status = Response::HTTP_CREATED;
        } catch (\Exception $exception) {
            DB::rollBack();
            $this->response['message'] = $exception->getMessage();
            $this->status = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json($this->response, $this->status);
    }

    /**
     * @throws Throwable
     */
    public function deleteDetail(FabricReceiveDetail $detail, FabricStockSummaryService $service): JsonResponse
    {

        $summary = $service->summary($detail);

        if (!$summary) {
            return response()->json(['message' => 'Summary is not found!'], 500);
        }

        try {
            if ($detail->receive_qty <= $summary->balance) {
                DB::beginTransaction();
                $detail->delete();
                DB::commit();
                $this->response['message'] = S_DEL_MSG;
            }
        } catch (Throwable $e) {
            DB::rollBack();
            $this->response['message'] = 'Something Went Wrong!';
            $this->response['errMsg'] = $e->getMessage();
            $this->statusCode = 400;
        }

        return response()->json($this->response, $this->statusCode);
    }

    public function view(FabricReceive $receive)
    {
        $receive->load('details.barcodeDetails', 'details.receiveReturnDetails');
        $variableSettings = FabricStoreVariableSetting::query()->first();
        $uomService = BudgetService::UOM;
        return view('inventory::fabrics.pages.fabric_receive_view.view', [
            'receive' => $receive,
            'variableSettings' => $variableSettings,
            'uomService' => $uomService
        ]);
    }

    public function pdf(FabricReceive $receive)
    {
        $variableSettings = FabricStoreVariableSetting::query()->first();
        $pdf = PDF::loadView('inventory::fabrics.pages.fabric_receive_view.pdf',
            compact('receive', 'variableSettings'))
            ->setPaper('a4')->setOrientation('landscape')
            ->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

        return $pdf->stream('fabric_receive_view.pdf');
    }

    public function approve(FabricReceive $receive): RedirectResponse
    {
        try {
            $receive->update(['status' => '1']);

            Session::flash('success', 'Data approve successfully!');
        } catch (\Exception $exception) {
            Session::flash('error', "Something went wrong! {$exception->getMessage()}");
        }

        return redirect()->back();
    }

    public function barcodes(FabricReceive $receive)
    {
        $receive->load('barcodeDetails');

        return view('inventory::fabrics.pages.fabric_barcodes', [
            'receive' => $receive,
        ]);
    }

    public function format($receive): array
    {
        return [
            'id' => $receive->id,
            'receive_no' => $receive->receive_no,
            'factory_id' => $receive->factory_id,
            'factory_location' => $receive->factory_location,
            'receive_date' => $receive->receive_date,
            'store_id' => $receive->store_id,
            'receive_basis' => $receive->receive_basis,
            'receivable_type' => $receive->receivable_type,
            'receivable_id' => $receive->receivable_id,
            'dyeing_source' => $receive->dyeing_source,
            'dyeing_supplier_type' => $receive->dyeing_supplier_type,
            'dyeing_supplier_id' => $receive->dyeing_supplier_id,
            'dyeing_supplier_address' => $receive->dyeing_supplier_address,
            'receive_challan' => $receive->receive_challan,
            'po_no' => $receive->po_no,
            'grey_issue_challan' => $receive->grey_issue_challan,
            'currency_id' => $receive->currency_id,
            'exchange_rate' => $receive->exchange_rate,
            'lc_sc_no' => $receive->lc_sc_no,
            'pi_offer_date' => $receive->pi_offer_date,
            'status' => $receive->status,
            'details' => $this->formatDetails($receive->details),
        ];
    }

    public function formatDetails($details)
    {
        return $details->map(function ($detail) {
            $styleName = Order::query()->findOrFail($detail->style_id)->style_name;
            $bookingQty = FabricBookingDetailsBreakdown::query()->where([
                'booking_id' => $detail->receivable_id,
                'garments_item_id' => $detail->gmts_item_id,
                'body_part_id' => $detail->body_part_id,
                'color_type_id' => $detail->color_type_id,
                'construction' => $detail->construction,
                'uom' => $detail->uom_id,
                'fabric_composition_id' => $detail->fabric_composition_id,
                'dia_type' => $detail->dia_type,
                'dia' => $detail->dia,
                'color_id' => $detail->color_id,
                'style_name' => $styleName
            ])->sum('actual_wo_qty');

            return [
                'id' => $detail->id,
                'unique_id' => $detail->unique_id,
                'receivable_type' => $detail->receivable_type,
                'receivable_id' => $detail->receivable_id,
                'receive_id' => $detail->receive_id,
                'buyer_id' => $detail->buyer_id,
                'style_id' => $detail->style_id,
                'style_name' => $detail->style_name,
                'po_no' => $detail->po_no,
                'batch_no' => $detail->batch_no,
                'gmts_item_id' => $detail->gmts_item_id,
                'body_part_id' => $detail->body_part_id,
                'body_part_value' => $detail->body->name,
                'fabric_composition_id' => $detail->fabric_composition_id,
                'construction' => $detail->construction,
                'fabric_description' => $detail->fabric_description,
                'dia' => $detail->dia,
                'ac_dia' => $detail->ac_dia,
                'gsm' => $detail->gsm,
                'ac_gsm' => $detail->ac_gsm,
                'dia_type' => $detail->dia_type,
                'ac_dia_type' => $detail->ac_dia_type,
                'color_id' => $detail->color_id,
                'color_name' => $detail->color->name,
                'contrast_color_id' => $detail->contrast_color_id,
                'uom_id' => $detail->uom_id,
                'uom_name' => $detail->uom->unit_of_measurement,
                'receive_qty' => $detail->receive_qty,
                'rate' => $detail->rate,
                'amount' => $detail->amount,
                'reject_qty' => $detail->reject_qty,
                'balance_qty' => $detail->receivable_type !== 'independent'
                    ? number_format($bookingQty - $detail->receive_qty, 4)
                    : '',
                'fabric_shade' => $detail->fabric_shade,
                'no_of_roll' => $detail->no_of_roll,
                'grey_used' => $detail->grey_used,
                'store_id' => $detail->store_id,
                'floor_id' => $detail->floor_id,
                'room_id' => $detail->room_id,
                'rack_id' => $detail->rack_id,
                'shelf_id' => $detail->shelf_id,
                'remarks' => $detail->remarks,
                'machine_name' => $detail->machine_name,
                'color_type_id' => $detail->color_type_id,
            ];
        });
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $fabricReceive = FabricReceive::query()->findOrFail($id);
            $fabricReceive->details()->delete();
            $fabricReceive->delete();
            DB::commit();
            Session::flash('success', 'Data deleted successfully!');
            return back();
        } catch (Throwable $exception) {
            Session::flash('error', "Something went wrong! {$exception->getMessage()}");
        }
    }

    public function excel(FabricReceive $receive)
    {
        $receive->load('details.barcodeDetails', 'details.receiveReturnDetails');
        $variableSettings = FabricStoreVariableSetting::query()->first();

        return Excel::download(new FabricReceiveViewExport($receive,$variableSettings), 'fabric_receive_view.xlsx');
    }

}
