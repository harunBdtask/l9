<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API\YarnReceive;

use SkylarkSoft\GoRMG\Inventory\Models\YarnIssue;
use SkylarkSoft\GoRMG\Inventory\Models\YarnIssueDetail;
use SkylarkSoft\GoRMG\Inventory\Models\YarnReceiveDetail;
use SkylarkSoft\GoRMG\Inventory\Models\YarnReceiveReturn;
use SkylarkSoft\GoRMG\Inventory\Services\YarnReceiveUpdateNotificationService;
use SkylarkSoft\GoRMG\Inventory\Services\YarnStockSummaryService;
use SkylarkSoft\GoRMG\Inventory\YarnItemAction;
use SkylarkSoft\GoRMG\SystemSettings\Services\YarnStoreApprovalMaintainService;
use Throwable;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Inventory\Filters\Filter;
use SkylarkSoft\GoRMG\Inventory\Models\YarnReceive;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnCount;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnComposition;
use SkylarkSoft\GoRMG\Inventory\Requests\YarnReceiveFormRequest;
use SkylarkSoft\GoRMG\Inventory\Services\YarnReceiveBasisBreakDownService;

class YarnReceiveController extends Controller
{
    public function index(Request $request)
    {
        $lcNo = $request->get('lc_no');
        $yarnLot = $request->get('yarn_lot');
        $yarnCount = $request->get('yarn_count');
        $challanNo = $request->get('challan_no');
        $receiveNo = $request->get('receive_no');
        $receiveDate = $request->get('receive_date');
        $receiveYear = $request->get('receive_year');
        $receiveBasis = $request->get('receive_basis');
        $supplierName = $request->get('supplier_name');
        $receiveBasisNo = $request->get('basis_no');
        $yarnComposition = $request->get('yarn_composition');
        $productCode = $request->get('product_code');

        $data['form'] = request()->all();
        $data['yarnLots'] = YarnReceiveDetail::query()
            ->pluck('yarn_lot')
            ->unique()->values();
        $data['receive_list'] = YarnReceive::query()
            ->with(['details.yarn_count','details.composition', 'yarnReceiveReturn.details'])
            ->withoutGlobalScope('approvalMaintain')
            ->when($receiveNo, Filter::applyFilter('receive_no', $receiveNo))
            ->when($lcNo, Filter::applyFilter('lc_no', $lcNo))
            ->when($supplierName, function ($query) use ($supplierName) {
                $query->whereHas('supplier', function ($supplier) use ($supplierName) {
                    $supplier->where('name', $supplierName);
                });
            })
            ->when($yarnLot, function ($query) use($yarnLot){
                $query->whereHas('details',function ($detailQuery) use($yarnLot){
                    $detailQuery->where('yarn_lot', $yarnLot);
                });
            })
            ->when($yarnCount, function ($query) use($yarnCount){
                $query->whereHas('details',function ($detailQuery) use($yarnCount){
                    $yarnCountId=optional(YarnCount::query()->where('yarn_count', $yarnCount)->first())->id;
                    $detailQuery->where('yarn_count_id', $yarnCountId);
                });
            })
            ->when($yarnComposition, function ($query) use($yarnComposition){
                $query->whereHas('details',function ($detailQuery) use($yarnComposition){
                    $yarnCompositionId=optional(YarnComposition::query()->where('yarn_composition', $yarnComposition)->first())->id;
                    $detailQuery->where('yarn_composition_id', $yarnCompositionId);
                });
            })
            ->when($productCode, function ($query) use($productCode){
                $query->whereHas('details',function ($detailQuery) use($productCode){
                    $detailQuery->where('product_code', $productCode);
                });
            })
            ->when($challanNo, Filter::applyFilter('challan_no', $challanNo))
            ->when($receiveDate, Filter::applyFilter('receive_date', $receiveDate))
            ->when($receiveYear, Filter::yearFilter('receive_date', $receiveYear))
            ->when($receiveDate, Filter::applyFilter('receive_date', $receiveDate))
            ->when($receiveBasis, Filter::applyFilter('receive_basis', $receiveBasis))
            ->when($receiveBasisNo, Filter::applyFilter('receive_basis_no', $receiveBasisNo))
            ->orderByDesc('id')->paginate();

        $data['approvalMaintain'] = YarnStoreApprovalMaintainService::getApprovalMaintainStatus();

        return view('inventory::yarns.receives.index', $data);
    }

    public function create()
    {
        return view('inventory::yarns.receives.create');
    }

    /**
     * @throws Throwable
     */
    public function store(YarnReceiveFormRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $yarnReceive = YarnReceive::query()
                ->withoutGlobalScope('approvalMaintain')
                ->firstOrNew(['id' => $request->input('id')]);
            $yarnReceive->fill($request->all())->save();
            DB::commit();

            if ($request->get('id')) {
                YarnReceiveUpdateNotificationService::for($yarnReceive)->notify();
            }

            return response()->json($this->data($yarnReceive), Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json([
                'msg' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'file' => $exception->getFile(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function edit($id): JsonResponse
    {
        try {
            $yarnReceive = YarnReceive::query()
                ->withoutGlobalScope('approvalMaintain')
                ->with('details')
                ->find($id);
            return response()->json($this->data($yarnReceive), Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    protected function data($yarnReceive): array
    {
        $approveMaintain = YarnStoreApprovalMaintainService::getApprovalMaintainStatus();
        $useStatus = false;
        if ($approveMaintain == 1 && $yarnReceive->is_approve == 1) {
            foreach ($yarnReceive->details as $yarn) {
                $yarnStockSummary = (new YarnStockSummaryService())->summary($yarn);

                if (!($yarn->receive_qty <= $yarnStockSummary->balance)) {
                    $useStatus = true;
                    break;
                }
            }
        }

        return [
            "id" => $yarnReceive->id,
            "store_id" => $yarnReceive->store_id,
            "factory_id" => $yarnReceive->factory_id,
            "receive_no" => $yarnReceive->receive_no,
            "receive_basis" => $yarnReceive->receive_basis,
            "receive_purpose" =>$yarnReceive->receive_purpose,
            "receive_basis_no" => $yarnReceive->receive_basis_no,
            "receive_basis_id" => $yarnReceive->receive_basis_id,
            "lc_no" => $yarnReceive->lc_no,
            "source" => $yarnReceive->source,
            "remarks" => $yarnReceive->remarks,
            "challan_no" => $yarnReceive->challan_no,
            "currency_id" => $yarnReceive->currency_id,
            "receive_date" =>$yarnReceive->receive_date,
            "exchange_rate" => $yarnReceive->exchange_rate,
            "loan_party_id" => $yarnReceive->loan_party_id,
            "lc_receive_date" => $yarnReceive->lc_receive_date,
            "issue_challan_no" => $yarnReceive->issue_challan_no,
            "ready_to_approve" => $yarnReceive->ready_to_approve,
            "un_approve_request" => $yarnReceive->un_approve_request,
            "step" => $yarnReceive->step,
            "is_approve" => $yarnReceive->is_approve,
            "approve_status" => $approveMaintain == 1 && $yarnReceive->is_approve == 1,
            "use_status" => $useStatus,
            "breakdown"=> (new YarnReceiveBasisBreakDownService($yarnReceive->receive_basis, $yarnReceive->receive_basis_id))->output(),
        ];
    }

    public function destroy($id): RedirectResponse
    {
        try {
            $yarnReceive = YarnReceive::query()
                ->withoutGlobalScope('approvalMaintain')
                ->findOrFail($id);
            if ($yarnReceive->details->count() || $yarnReceive->yarnReceiveReturn->count()) {
                return redirect()->back()->with('danger', E_DEL_MSG);
            }
            $yarnReceive->delete();
            return redirect()->back()->with('success', S_DELETE_MSG);
        } catch (Exception $exception) {
            return redirect()->back()->with('danger', E_DEL_MSG);
        }
    }

    public function getReceiveIds(): JsonResponse
    {
        try {
            $receiveIds = YarnReceive::query()
                ->pluck('receive_no');
            return response()->json($receiveIds, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
