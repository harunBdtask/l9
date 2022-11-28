<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\Report;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Merchandising\Filters\Filter;
use SkylarkSoft\GoRMG\Merchandising\Models\Samples\SampleRequisition;

class SampleSummaryReportService
{
    public function getReportData(Request $request)
    {
        $fromDate = $request->from_date ? Carbon::make($request->from_date)->format('Y-m-d') : date('Y-m-d');
        $toDate = $request->to_date ? Carbon::make($request->to_date)->format('Y-m-d') : date('Y-m-d');
        $search = $request->get('search');
        $dealing_merchant_id = $request->get('dealing_merchant_id');
        $buyer_id = $request->get('buyer_id');
        $style_id = $request->get('style_id');
        $sample_stage = $request->get('sample_stage');
        $sampleId = $request->get('sample_id');
        $deliveryStatus = $request->get('delivery_status');

        return SampleRequisition::query()
            ->with([
                'merchant',
                'details',
                'department'
            ])
            ->whereHas('details', function ($query) use ($sampleId) {
                $query->when($sampleId, Filter::applyFilter('sample_id', $sampleId));
            })
            ->when($search, function (Builder $query) use ($search) {
                $stageId = collect(SampleRequisition::SAMPLE_STAGES)->map(function ($type, $key) use ($search) {
                    return $type == $search ? $key : '';
                })->filter(function ($value) {
                    return $value != '';
                })->first();
                $query->when(!$stageId, function (Builder $query) use ($search) {
                    $query->where('requisition_id', 'LIKE', '%' . $search . '%')
                        ->orWhere('req_date', 'LIKE', '%' . $search . '%')
                        ->orWhere('style_name', 'LIKE', '%' . $search . '%')
                        ->orWhereHas('merchant', function ($query) use ($search) {
                            $query->where('screen_name', 'LIKE', '%' . $search . '%');
                        })
                        ->orWhereHas('buyer', function ($query) use ($search) {
                            $query->where('name', 'LIKE', '%' . $search . '%');
                        })
                        ->orWhereHas('department', function ($query) use ($search) {
                            $query->where('product_department', 'LIKE', '%' . $search . '%');
                        })
                        ->orWhere('remarks', 'LIKE', '%' . $search . '%');
                })->when($stageId, function (Builder $query) use ($stageId) {
                    $query->where('sample_stage', 'LIKE', '%' . $stageId . '%');
                });
            })
            ->when($dealing_merchant_id, Filter::applyFilter('dealing_merchant_id', $dealing_merchant_id))
            ->when($buyer_id, Filter::applyFilter('buyer_id', $buyer_id))
            ->when($style_id, Filter::applyFilter('style_name', $style_id))
            ->when($sample_stage, Filter::applyFilter('sample_stage', $sample_stage))
            ->when($deliveryStatus && $deliveryStatus == '1', function (Builder $query) use ($deliveryStatus) {
                $query->whereNotNull('delivery_date');
            })->when($deliveryStatus && $deliveryStatus == '2', function (Builder $query) use ($deliveryStatus) {
                $query->whereNull('delivery_date');
            })
            ->whereBetween('req_date', [$fromDate, $toDate])
            ->get();
    }
}
