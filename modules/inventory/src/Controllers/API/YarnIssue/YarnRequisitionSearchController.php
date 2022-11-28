<?php
namespace SkylarkSoft\GoRMG\Inventory\Controllers\API\YarnIssue;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\Inventory\Models\YarnIssueDetail;
use SkylarkSoft\GoRMG\Inventory\Models\YarnStockSummary;
use SkylarkSoft\GoRMG\Inventory\Services\YarnStockSummaryService;
use SkylarkSoft\GoRMG\Inventory\YarnItemAction;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Knitting\Models\YarnRequisition;

class YarnRequisitionSearchController extends Controller
{

    protected function getCurrentStock($lot,$store_id){
        return optional(YarnStockSummary::query()
            ->where('store_id', $store_id)
            ->where('yarn_lot', $lot)
            ->first())->balance;
    }
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $self=$this;
            $requisition = YarnRequisition::query()->with([
                'program',
                'details.type',
                'program.planInfo',
                'details.yarn_count',
                'details.composition',
                'details.knittingProgramColor.itemColor',
                'program.planInfo.programmable'
            ])
                ->when($request->get('requisition_no'), function ($query) use ($request) {
                    return $query->where('requisition_no', $request->get('requisition_no'));
                })
                ->when($request->get('yarn_lot'), function ($query) use ($request) {
                    return $query->whereHas('details', function ($detailQuery) use ($request) {
                        return $detailQuery->where('yarn_lot', $request->get('yarn_lot'));
                    });
                })
                ->get()->map(function ($requisition) use($self,$request) {
                    $details = collect($requisition->details);
                    $requisitionDetails = $details->map(function ($requisitionDetails) use($self, $request, $requisition) {
                        $stockSummary = (new YarnStockSummaryService())->summary($requisitionDetails, true);
                        $totalReqQty = $requisitionDetails->requisition_qty;
                        $totalIssueQty = YarnIssueDetail::query()->where('demand_no', $requisition->requisition_no)
                            ->where(YarnItemAction::itemCriteria($requisitionDetails))
                            ->where(function ($query) use($requisitionDetails) {
                                $query->where('requisition_color_id', $requisitionDetails->knitting_program_color_id)
                                    ->orWhereNull('requisition_color_id');
                            })->sum('issue_qty');
                        $rate = isset($stockSummary) ? $stockSummary->balance_amount / $stockSummary->balance : 0;

                        return [
                            'yarn_count' => $requisitionDetails->yarn_count->yarn_count,
                            'yarn_type' => $requisitionDetails->type->yarn_type,

                            'yarn_count_id' => $requisitionDetails->yarn_count_id,
                            'yarn_composition_id' => $requisitionDetails->yarn_composition_id,
                            'yarn_type_id' => $requisitionDetails->yarn_type_id,
                            'yarn_lot' => $requisitionDetails->yarn_lot,
                            'store_id' => $requisitionDetails->store_id,
                            'yarn_color' => $requisitionDetails->yarn_color,
                            'yarn_brand' => $requisitionDetails->yarn_brand,
                            'uom_id' => $requisitionDetails->uom_id,

                            'current_stock' => $stockSummary->balance ?? 0,
                            'rate' => numberFormat($rate),
                            'total_issue_qty' => $totalIssueQty,
                            'total_req_qty' => $totalReqQty,
                            'total_issuable_qty' => number_format($totalReqQty - $totalIssueQty, 4, '.', ''),
                            'issue_qty' => $requisitionDetails->requisition_qty,
                            'requisition_qty' => $requisitionDetails->requisition_qty,
                            'requisition_date' => $requisitionDetails->requisition_date,
                            'yarn_composition' => $requisitionDetails->composition->yarn_composition,
                            'knitting_program_color_id' => $requisitionDetails->knitting_program_color_id,
                            'color' => $requisitionDetails->knittingProgramColor->itemColor->name,
                        ];
                    });
                    return [
                        'program_no' => $requisition->program->program_no,
                        'style_name' => optional($requisition->program->planInfo)->style_name,
                        'buyer_id' => optional($requisition->program->planInfo->programmable)->buyer_id,
                        'order_number' => optional($requisition->program->planInfo->programmable)->order_number,
                        'buyer_name' => $requisition->program->planInfo->buyer_name,
                        'requisition_no' => $requisition->requisition_no,
                        'booking_type' => !empty($requisition->program->planInfo->booking_type) ? $requisition->program->planInfo->booking_type : 'main',
                        'details' => $requisitionDetails
                    ];
                });
            return response()->json($requisition, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

