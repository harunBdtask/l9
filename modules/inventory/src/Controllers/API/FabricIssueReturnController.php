<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API;

use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Constants\ApplicationConstant;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;
use App\Exceptions\DeleteNotPossibleException;
use SkylarkSoft\GoRMG\Inventory\Services\FabricStore\BalanceQty;
use SkylarkSoft\GoRMG\Inventory\Requests\FabricIssueReturnRequest;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricIssueReturn;
use SkylarkSoft\GoRMG\Inventory\Requests\FabricIssueReturnDetailRequest;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricIssueReturnDetail;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBookingDetailsBreakdown;
use SkylarkSoft\GoRMG\Inventory\Services\FabricStore\FabricIssueReturn\FabricIssueReturnStrategy;

class FabricIssueReturnController extends Controller
{
    public $response = [];
    public $status = 200;

    public function getIssueDetails(Request $request)
    {
        return (new FabricIssueReturnStrategy())->setStrategy($request->get('request_type'))
            ->setRequest($request)
            ->getIssueDetails();
    }

    public function index()
    {
        $issueReturns = FabricIssueReturn::query()->orderByDesc('id')->paginate();

        return view('inventory::fabrics.pages.fabric-issue-returns', compact('issueReturns'));
    }

    public function create()
    {
        return view('inventory::fabrics.issue-return');
    }

    public function store(FabricIssueReturnRequest $request, FabricIssueReturn $fabricIssueReturn): JsonResponse
    {
        try {
            $fabricIssueReturn->fill($request->all())->save();

            $this->response['message'] = ApplicationConstant::S_CREATED;
            $this->response['data'] = $fabricIssueReturn;
            $this->status = Response::HTTP_CREATED;
        } catch (\Exception $exception) {
            $this->response['message'] = $exception->getMessage();
            $this->status = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json($this->response, $this->status);
    }

    /**
     * @throws Throwable
     */
    public function storeDetail(FabricIssueReturnDetailRequest $request, FabricIssueReturn $fabricIssueReturn): JsonResponse
    {
        try {
            DB::beginTransaction();
            $fabricIssueReturnDetail = (new FabricIssueReturnStrategy())->setStrategy($request->get('issue_return_type'))
                ->setRequest($request)
                ->setIssueReturnModel($fabricIssueReturn)
                ->storeDetail();
            DB::commit();

            $this->response['message'] = ApplicationConstant::S_CREATED;
            $this->response['data'] = $this->formatDetailData($fabricIssueReturnDetail);
            $this->status = Response::HTTP_CREATED;
        } catch (\Exception $exception) {
            DB::rollBack();
            $this->response['message'] = $exception->getMessage();
            $this->status = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json($this->response, $this->status);
    }

    public function show(FabricIssueReturn $fabricIssueReturn): array
    {
        $fabricIssueReturn->load('details');

        return [
            'issue_return_no' => $fabricIssueReturn->issue_return_no,
            'factory_id' => $fabricIssueReturn->factory_id,
            'return_date' => $fabricIssueReturn->return_date,
            'issue_no' => $fabricIssueReturn->issue_no,
            'challan_no' => $fabricIssueReturn->challan_no,
            'details' => $fabricIssueReturn->details->map(function ($detail) {
                return $this->formatDetailData($detail);
            }),
        ];
    }

    public function destroy(FabricIssueReturn $issueReturn): RedirectResponse
    {
        try {
            if (count($issueReturn->details()->get())) {
                throw new DeleteNotPossibleException();
            }
            $issueReturn->delete();
            Session::flash('success', 'Data Deleted successfully!');
        } catch (\Exception $exception) {
            Session::flash('error', "Something went wrong! {$exception->getMessage()}");
        }

        return redirect()->back();
    }

    public function formatDetailData($fabricIssueReturnDetail): array
    {
        $bookingId = $fabricIssueReturnDetail->issue_return_type == FabricIssueReturnDetail::BARCODE
            ? $fabricIssueReturnDetail->issueDetail->barcodeDetail->receiveDetail->receivable_id
            : $fabricIssueReturnDetail->issueDetail->receiveDetail->receivable_id;

        $uomName = FabricBookingDetailsBreakdown::query()->where([
            'booking_id' => $bookingId,
            'garments_item_id' => $fabricIssueReturnDetail->gmts_item_id,
            'color_type_id' => $fabricIssueReturnDetail->color_type_id,
            'construction' => $fabricIssueReturnDetail->construction,
            'uom' => $fabricIssueReturnDetail->uom_id,
            'fabric_composition_id' => $fabricIssueReturnDetail->fabric_composition_id,
            'dia_type' => $fabricIssueReturnDetail->dia_type,
            'dia' => $fabricIssueReturnDetail->dia,
            'color_id' => $fabricIssueReturnDetail->color_id,
        ])->first()['uom_value'];

        $balanceQty = (new BalanceQty)->balance($fabricIssueReturnDetail);

        return [
            'id' => $fabricIssueReturnDetail->id,
            'store_id' => $fabricIssueReturnDetail->store_id,
            'store_name' => $fabricIssueReturnDetail->store->name,
            'buyer_id' => $fabricIssueReturnDetail->buyer_id,
            'style_id' => $fabricIssueReturnDetail->style_id,
            'style_name' => $fabricIssueReturnDetail->style_name,
            'construction' => $fabricIssueReturnDetail->construction,
            'unique_id' => $fabricIssueReturnDetail->unique_id,
            'batch_no' => $fabricIssueReturnDetail->batch_no,
            'fabric_color_id' => $fabricIssueReturnDetail->color_id,
            'fabric_color_name' => $fabricIssueReturnDetail->color->name,
            'fabric_shade' => $fabricIssueReturnDetail->fabric_shade,
            'fabric_description' => $fabricIssueReturnDetail->fabric_description,
            'dia' => $fabricIssueReturnDetail->dia,
            'gsm' => $fabricIssueReturnDetail->gsm,
            'dia_type' => $fabricIssueReturnDetail->dia_type,
            'color_id' => $fabricIssueReturnDetail->color_id,
            'color' => $fabricIssueReturnDetail->color->name,
            'sample_type' => $fabricIssueReturnDetail->sample_type,
            'uom_id' => $fabricIssueReturnDetail->uom_id,
            'uom_name' => $uomName,
            'floor_id' => $fabricIssueReturnDetail->floor_id,
            'floor_name' => $fabricIssueReturnDetail->floor->name,
            'room_id' => $fabricIssueReturnDetail->room_id,
            'room_name' => $fabricIssueReturnDetail->room->name,
            'rack_id' => $fabricIssueReturnDetail->rack_id,
            'rack_name' => $fabricIssueReturnDetail->rack->name,
            'shelf_id' => $fabricIssueReturnDetail->shelf_id,
            'shelf_name' => $fabricIssueReturnDetail->shelf->name,
            'receive_qty' => $fabricIssueReturnDetail->receive_qty,
            'issue_qty' => $fabricIssueReturnDetail->issueDetail->issue_qty,
            'return_qty' => $fabricIssueReturnDetail->return_qty,
            'amount' => $fabricIssueReturnDetail->amount,
            'balance_qty' => $balanceQty,
            'gmts_item_id' => $fabricIssueReturnDetail->gmts_item_id,
            'gmts_item_name' => $fabricIssueReturnDetail->gmtsItem->name,
            'body_part_id' => $fabricIssueReturnDetail->body_part_id,
            'body_part_value' => $fabricIssueReturnDetail->bookingDetail->body_part_value,
            'rate' => $fabricIssueReturnDetail->rate,
            'no_of_roll' => $fabricIssueReturnDetail->no_of_roll,
            'cutting_unit_no' => $fabricIssueReturnDetail->cutting_unit_no,
            'remarks' => $fabricIssueReturnDetail->remarks,
            'fabric_composition_id' => $fabricIssueReturnDetail->fabric_composition_id,
            'color_type_id' => $fabricIssueReturnDetail->color_type_id,
            'issue_qty_details' => $fabricIssueReturnDetail->issue_qty_details,
            'po_no' => collect($fabricIssueReturnDetail->receiveDetail->receive->po_no)->join(' ,'),
        ];
    }
}
