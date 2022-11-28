<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API;

use PDF;
use Excel;
use Exception;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricReceive;
use SkylarkSoft\GoRMG\Merchandising\Services\Budget\BudgetService;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Constants\ApplicationConstant;
use Illuminate\Support\Facades\Session;
use App\Exceptions\DeleteNotPossibleException;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Inventory\Requests\FabricIssueRequest;
use SkylarkSoft\GoRMG\Inventory\Exports\FabricIssueViewExport;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricIssue;
use SkylarkSoft\GoRMG\Inventory\Services\FabricStore\BalanceQty;
use SkylarkSoft\GoRMG\Inventory\Requests\FabricIssueDetailRequest;
use SkylarkSoft\GoRMG\Inventory\Services\FabricStockSummaryService;
use SkylarkSoft\GoRMG\Inventory\Exceptions\SummaryNotFoundException;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricIssueDetail;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricStockSummary;
use SkylarkSoft\GoRMG\Inventory\Services\FabricStore\FabricIssue\FabricIssueStrategy;
use SkylarkSoft\GoRMG\SystemSettings\Models\ServiceCompany;

class FabricIssueController extends Controller
{
    public $response = [];
    public $status = 200;

    public function getReceiveDetails(Request $request)
    {
        return (new FabricIssueStrategy())->setStrategy($request->get('request_type'))
            ->setRequest($request)
            ->getDetail();
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        $issues = FabricIssue::query()->search($search)->orderByDesc('id')->paginate();
        return view('inventory::fabrics.pages.fabric-issues', compact('issues'));
    }

    public function create()
    {
        return view('inventory::fabrics.issue');
    }

    public function store(FabricIssueRequest $request, FabricIssue $fabricIssue): JsonResponse
    {
        try {
            $fabricIssue->fill($request->all())->save();
            $this->response['message'] = ApplicationConstant::S_CREATED;
            $this->response['data'] = $fabricIssue;
            $this->status = Response::HTTP_CREATED;
        } catch (Exception $exception) {
            $this->response['message'] = $exception->getMessage();
            $this->status = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json($this->response, $this->status);
    }

    /**
     * @throws Throwable
     */
    public function storeDetail(FabricIssueDetailRequest $request, FabricIssue $fabricIssue): JsonResponse
    {
//        dd($request->all());
        try {
            DB::beginTransaction();
            $fabricIssueDetail = (new FabricIssueStrategy())
                ->setStrategy($request->get('issue_type'))
                ->setRequest($request)
                ->setIssueModel($fabricIssue)
                ->storeDetail();
            DB::commit();

            $this->response['message'] = ApplicationConstant::S_CREATED;
            $this->response['data'] = $this->formatDetailData($fabricIssueDetail);
            $this->status = Response::HTTP_CREATED;
        } catch (Exception $exception) {
            DB::rollBack();
            $this->response['message'] = $exception->getMessage();
            $this->status = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json($this->response, $this->status);
    }

    public function show(FabricIssue $fabricIssue): array
    {
        $fabricIssue->load('details');

        return [
            'id'                   => $fabricIssue['id'],
            'buyer_id'             => $fabricIssue['buyer_id'],
            'challan_no'           => $fabricIssue['challan_no'],
            'cutt_req_no'          => $fabricIssue['cutt_req_no'],
            'factory_id'           => $fabricIssue['factory_id'],
            'issue_date'           => $fabricIssue['issue_date'],
            'issue_no'             => $fabricIssue['issue_no'],
            'issue_purpose'        => $fabricIssue['issue_purpose'],
            'service_company_id'   => $fabricIssue['service_company_id'],
            'service_company_type' => $fabricIssue['service_company_type'],
            'service_location'     => $fabricIssue['service_location'],
            'service_source'       => $fabricIssue['service_source'],
            'details'              => $fabricIssue['details']->map(function ($detail) {
                return $this->formatDetailData($detail);
            })
        ];
    }

    public function update(FabricIssueRequest $request, FabricIssue $fabricIssue): JsonResponse
    {
        try {
            $fabricIssue->fill($request->all())->save();
            $this->response['message'] = ApplicationConstant::S_UPDATED;
            $this->response['data'] = $fabricIssue;
            $this->status = Response::HTTP_CREATED;
        } catch (Exception $exception) {
            $this->response['message'] = $exception->getMessage();
            $this->status = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json($this->response, $this->status);
    }

    public function destroy(FabricIssue $issue): RedirectResponse
    {
        try {
            if (count($issue->details()->get())) {
                throw new DeleteNotPossibleException();
            }
            $issue->delete();
            Session::flash('success', 'Data Deleted successfully!');
        } catch (\Exception $exception) {
            Session::flash('error', "Something went wrong! {$exception->getMessage()}");
        }

        return redirect()->back();
    }

    /**
     * @throws Exception
     * @throws Throwable
     */
    public function destroyDetail(FabricIssueDetail $fabricIssueDetail, FabricStockSummaryService $service): JsonResponse
    {
        $summery = $service->summary($fabricIssueDetail);

        if (!$summery) {
            throw new SummaryNotFoundException('Summery data not found');
        }

        try {
            DB::beginTransaction();
            if (($summery->issue_qty - $summery->issue_return_qty) >= $fabricIssueDetail->issue_qty) {
                $fabricIssueDetail->delete();
            }
            DB::commit();

            $this->response['message'] = ApplicationConstant::S_DELETED;
        } catch (Exception $exception) {
            DB::rollBack();
            $this->response['message'] = ApplicationConstant::SOMETHING_WENT_WRONG;
            $this->response['errMsg'] = $exception->getMessage();
            $this->status = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json($this->response, $this->status);
    }

    public function formatDetailData($fabricIssueDetail): array
    {
        $balanceQty = (new BalanceQty())->balance($fabricIssueDetail);

        return [
            'id'                    => $fabricIssueDetail->id,
            'po_no'                 => $fabricIssueDetail->receiveDetail->po_no,
            'prod_id'               => null,
            'store_id'              => $fabricIssueDetail->store_id,
            'buyer_id'              => $fabricIssueDetail->buyer_id,
            'store_name'            => $fabricIssueDetail->store->name,
            'style_id'              => $fabricIssueDetail->style_id,
            'style_name'            => $fabricIssueDetail->style_name,
            'construction'          => $fabricIssueDetail->construction,
            'unique_id'             => $fabricIssueDetail->unique_id,
            'batch_no'              => $fabricIssueDetail->batch_no,
            'fabric_color_id'       => $fabricIssueDetail->color_id,
            'fabric_color_name'     => $fabricIssueDetail->color->name,
            'fabric_shade'          => $fabricIssueDetail->fabric_shade,
            'fabric_description'    => $fabricIssueDetail->fabric_description,
            'dia'                   => $fabricIssueDetail->dia,
            'ac_dia'                => $fabricIssueDetail->ac_dia,
            'gsm'                   => $fabricIssueDetail->gsm,
            'ac_gsm'                => $fabricIssueDetail->ac_gsm,
            'dia_type'              => $fabricIssueDetail->dia_type,
            'color_id'              => $fabricIssueDetail->color_id,
            'color'                 => $fabricIssueDetail->color->name,
            'sample_type'           => $fabricIssueDetail->sample_type,
            'issue_id'              => $fabricIssueDetail->issue_id,
            'uom_id'                => $fabricIssueDetail->uom_id,
            'uom_name'              => $fabricIssueDetail->receive->receive_basis === FabricReceive::INDEPENDENT_BASIS
                                        ? $fabricIssueDetail->uom->unit_of_measurement
                                        : FabricIssueDetail::UOM[$fabricIssueDetail->uom_id],

            'floor_id'              => $fabricIssueDetail->floor_id,
            'floor_name'            => $fabricIssueDetail->floor->name,
            'room_id'               => $fabricIssueDetail->room_id,
            'room_name'             => $fabricIssueDetail->room->name,
            'rack_id'               => $fabricIssueDetail->rack_id,
            'rack_name'             => $fabricIssueDetail->rack->name,
            'shelf_id'              => $fabricIssueDetail->shelf_id,
            'shelf_name'            => $fabricIssueDetail->shelf->name,
            'receive_qty'           => $fabricIssueDetail->receive_qty,
            'issue_qty'             => $fabricIssueDetail->issue_qty,
            'amount'                => $fabricIssueDetail->amount,
            'balance_qty'           => $balanceQty,
            'gmts_item_id'          => $fabricIssueDetail->gmts_item_id,
            'gmts_item_name'        => $fabricIssueDetail->gmtsItem->name,
            'body_part_id'          => $fabricIssueDetail->body_part_id,
            'body_part_value'       => $fabricIssueDetail->bookingDetail->body_part_value,
            'rate'                  => $fabricIssueDetail->rate,
            'no_of_roll'            => $fabricIssueDetail->no_of_roll,
            'cutting_unit_no'       => $fabricIssueDetail->cutting_unit_no,
            'remarks'               => $fabricIssueDetail->remarks,
            'fabric_composition_id' => $fabricIssueDetail->fabric_composition_id,
            'color_type_id'         => $fabricIssueDetail->color_type_id,
            'issue_qty_details'     => collect($fabricIssueDetail->issue_qty_details)->map(function ($qtyDetail) use ($balanceQty, $fabricIssueDetail) {
                return [
                    'po_no'          => $qtyDetail['po_no'],
                    'po_qty'         => $qtyDetail['po_qty'],
                    'ref_no'         => $qtyDetail['ref_no'],
                    'file_no'        => $qtyDetail['file_no'],
                    'req_qty'        => $qtyDetail['req_qty'],
                    'issue_qty'      => $qtyDetail['issue_qty'],
                    'balance_qty'    => $balanceQty,
                    'receive_qty'    => $qtyDetail['receive_qty'],
                    'shipment_date'  => $qtyDetail['shipment_date'],
                    'cumu_issue_qty' => $qtyDetail['cumu_issue_qty'],
                    'no_of_roll'     => $qtyDetail['no_of_roll'] ?? $fabricIssueDetail->no_of_roll,
                ];
            }),
        ];
    }

    public function view(FabricIssue $issue)
    {
        $type = '';
        $issue->load('details.uom');
        $uomService = FabricIssueDetail::UOM;
        return view('inventory::fabrics.pages.fabric_issue_view.view', compact('issue','type','uomService'));
    }

    public function pdf(FabricIssue $issue)
    {
        $type = '';
        $pdf = PDF::loadView('inventory::fabrics.pages.fabric_issue_view.pdf',
            compact('issue','type'))
            ->setPaper('a4')->setOrientation('landscape')
            ->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

        return $pdf->stream('fabric_issue_view.pdf');
    }

    public function approve(FabricIssue $issue): RedirectResponse
    {
        try {
            $issue->update(['status' => FabricIssue::APPROVE]);

            Session::flash('success', 'Data approve successfully!');
        } catch (\Exception $exception) {
            Session::flash('error', "Something went wrong! {$exception->getMessage()}");
        }

        return redirect()->back();
    }

    /**
     * @throws Throwable
     */
    public function saveRemarks(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            foreach ($request->all() as $detail) {
                $issueDetail = FabricIssueDetail::query()->findOrFail($detail['id']);
                $issueDetail->update(['remarks' => $detail['remarks']]);
            }
            DB::commit();

            $this->response['message'] = 'Remarks saved successfully';
            $this->status = Response::HTTP_CREATED;
        } catch (Exception $exception) {
            DB::rollBack();
            $this->response['message'] = "Something wend wrong! {$exception->getMessage()}";
            $this->status = Response::HTTP_INTERNAL_SERVER_ERROR;
        } finally {
            return response()->json($this->response, $this->status);
        }
    }

    public function excel(FabricIssue $issue)
    {
        $type = 'excel';
        return Excel::download(new FabricIssueViewExport($issue,$type), 'fabric_issue_view.xlsx');
    }

    public function getServiceCompany()
    {
        try {
            $serviceCompany = ServiceCompany::query()->get(['id', 'name as text']);
            return response()->json([
                'message' => 'Fetch Service Company successfully 🙂',
                'data' => $serviceCompany,
                'status' => Response::HTTP_OK
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
