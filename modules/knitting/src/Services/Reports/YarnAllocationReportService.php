<?php

namespace SkylarkSoft\GoRMG\Knitting\Services\Reports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Inventory\Models\YarnIssueDetail;
use SkylarkSoft\GoRMG\Inventory\Models\YarnReceiveDetail;
use SkylarkSoft\GoRMG\Inventory\Services\YarnIssue\YarnIssueStockSummaryService;
use SkylarkSoft\GoRMG\Inventory\YarnItemAction;
use SkylarkSoft\GoRMG\Knitting\Filters\Filter;
use SkylarkSoft\GoRMG\Knitting\Models\FabricSalesOrder;
use SkylarkSoft\GoRMG\Knitting\Models\KnittingProgram;
use SkylarkSoft\GoRMG\Knitting\Models\YarnAllocationDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnCount;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnType;

class YarnAllocationReportService
{
    private $from_date;
    private $to_date;
    private $yarn_lot;
    private $yarn_count;
    private $yarn_color;
    private $yarn_type;
    private $yarn_brand;


    public function __construct($request)
    {
        $this->from_date = $request->get('from_date');
        $this->to_date = $request->get('to_date');
        $this->yarn_lot = $request->get('yarn_lot');
        $this->yarn_count = $request->get('yarn_count');
        //$this->yarn_ref = $request->get('yarn_ref');
        $this->yarn_color = $request->get('yarn_color');
        $this->yarn_type = $request->get('yarn_type');
        $this->yarn_brand = $request->get('yarn_brand');
    }

    public function report()
    {
        return YarnAllocationDetail::query()
            ->with(['program.planInfo', 'programColor'])
            ->when($this->yarn_lot, Filter::applyFilter('yarn_lot', $this->yarn_lot))
            ->whereDate('created_at', '>=', $this->from_date)
            ->whereDate('created_at', '<=', $this->to_date)
            ->latest()
            ->get()->groupBy('knitting_program_id')->map(function ($programWiseAllocation) {
                return $programWiseAllocation->map(function ($allocation) {
                    $itemTotalAllocatedQty = $this->getYarnAllocatedQty($allocation);
                    $yarnRef = $this->getYarnRef($allocation);
                    $summary = (new YarnIssueStockSummaryService)->summary($allocation);
                    $totalIssueQty = $this->getYarnIssueQty($allocation);
                    $unallocatedQty = $summary->balance - $itemTotalAllocatedQty;
                    return [
                        'buyer' => $allocation->program->planInfo->buyer_name ?? '',
                        'style_name' => $allocation->program->planInfo->style_name ?? '',
                        'party_name' => $allocation->program->party_name ?? '',
                        'unique_id' => $allocation->program->planInfo->unique_id ?? '',
                        'within_group' => isset($allocation->program->planInfo->programmable) ? FabricSalesOrder::WITHIN_GROUP[$allocation->program->planInfo->programmable->within_group] : '',
                        'program_no' => $allocation->program->program_no ?? '',
                        'program_qty' => $allocation->program->program_qty ?? '',
                        'stitch_length' => $allocation->program->stitch_length ?? '',
                        'program_date' => $allocation->program->program_date ?? '',
                        'remarks' => $allocation->program->remarks ?? '',
                        'color' => $allocation->programColor->item_color,
                        'program_color_qty' => $allocation->programColor->program_qty,
                        'yarn_lot' => $allocation->yarn_lot,
                        'yarn_brand' => $allocation->yarn_brand,
                        'yarn_color' => $allocation->yarn_color,
                        'yarn_description' => $allocation->yarn_description,
                        'yarn_count' => explode(', ', $allocation->yarn_description)[0] ?? null, //$yarnCounts[$yarn->yarn_count_id] ?? '',
                        'yarn_ref' => $yarnRef,
                        'total_allocated_qty' => $itemTotalAllocatedQty,
                        'balance' => $summary->balance ?? 0,
                        'issue_qty' => $totalIssueQty,
                        'rem_issue_qty' => $itemTotalAllocatedQty > 0 ? (($itemTotalAllocatedQty - $totalIssueQty) ?? 0) : $totalIssueQty,
                        'unallocated_qty' => max($unallocatedQty, 0),
                    ];
                });
            });

    }

    public function getYarnAllocatedQty($yarn)
    {
        return YarnAllocationDetail::query()
            ->where(YarnItemAction::itemCriteria($yarn))
            ->where('knitting_program_color_id', $yarn->knitting_program_color_id)
            ->sum('allocated_qty');
    }

    public function getYarnIssueQty($yarn)
    {
        return YarnIssueDetail::query()
            ->whereHas('issue', function ($q) {
                $q->where('issue_basis', 2);
            })
            ->where(YarnItemAction::itemCriteria($yarn))
            ->sum('issue_qty');
    }

    private function getYarnRef($yarn): string
    {
        return YarnReceiveDetail::query()
                ->where(YarnItemAction::itemCriteria($yarn))
                ->first()->product_code ?? '';
    }
}
