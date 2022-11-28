<?php


namespace SkylarkSoft\GoRMG\Commercial\Controllers\BTBMarginLC;

use App\Constants\ApplicationConstant;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use SkylarkSoft\GoRMG\Commercial\Models\B2BMarginLCDetail;
use SkylarkSoft\GoRMG\Commercial\Models\ExportLC;
use SkylarkSoft\GoRMG\Commercial\Models\PrimaryMasterContract\PrimaryMasterContract;
use SkylarkSoft\GoRMG\Commercial\Models\SalesContract;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;

class LcScSearchController extends Controller
{
    public function search(Request $request)
    {
        if ($request->get('lc_sc') == ApplicationConstant::SEARCH_BY_LC) {
            $data = ExportLC::query()
                ->withCount('btbLcDetails as total_use')
                ->with('beneficiary')
                ->whereJsonContains('buyer_id', $request->get('buyer_id'))
                ->when($request->get('lc_number'), function ($q) use ($request) {
                    $q->where('lc_number', 'LIKE', '%' . $request->get('lc_number') . '%');
                })->get()->map(function ($datum) use ($request) {
                    return $this->format($datum, 1);
                });
        } else if($request->get('lc_sc') == ApplicationConstant::SEARCH_BY_PMC){
            $data = PrimaryMasterContract::query()
                ->withCount('btbLcDetails as total_use')
                ->with('beneficiary')
                ->whereJsonContains('buyer_id', $request->get('buyer_id'))
                ->get()->map(function ($datum) use ($request){
                    return $this->format($datum, 3);
                });
        } else{
             $data = SalesContract::query()
                ->withCount('btbLcDetails as total_use')
                ->with('beneficiary')
                ->whereJsonContains('buyer_id', $request->get('buyer_id'))
                ->when($request->get('lc_number'), function ($q) use ($request) {
                    $q->where('lc_number', 'LIKE', '%' . $request->get('lc_number') . '%');
                })->get()->map(function ($datum) use ($request) {
                    return $this->format($datum, 2);
                });
        }

        return response()->json($data, Response::HTTP_OK);
    }

    private function format($collection, $type)
    {
        if ($type == 1) {
            $prevCumulativeDistribution = B2BMarginLCDetail::query()
                ->where('export_lc_id', $collection->id)
                ->sum('current_distribution');
            $lc_sc_value = $collection->lc_value ?? 0;
            $max_current_distribution = $lc_sc_value - $prevCumulativeDistribution;

            return [
                'export_lc_id' => $collection->id,
                'lc_sc_type' => 'LC',
                'factory_id' => $collection->beneficiary_id,
                'beneficiary' => $collection->beneficiary->factory_name,
                'buyer_id' => request()->get('buyer_id'),
                'buyer' => request()->get('buyer_id') ? collect($collection->buyer_names)->where('id', request()->get('buyer_id'))->first()['name'] ?? null : null,
                'internal_file_no' => $collection->internal_file_no,
                'year' => $collection->year,
                'lc_sc_value' => $collection->lc_value,
                'lc_sc_no' => $collection->lc_number,
                'date' => $collection->lc_date,
                'old_current_distribution' => 0,
                'cumulative_distribution' => $prevCumulativeDistribution,
                'cumulative_distribution_calculation' => $prevCumulativeDistribution,
                'max_current_distribution' => $max_current_distribution,
                'status' => 1,
                'total_use' => $collection->total_use
            ];
        } elseif ($type == 3){
            $prevCumulativeDistribution = B2BMarginLCDetail::query()
                ->where('primary_master_contract_id', $collection->id)
                ->sum('current_distribution');
            $lc_sc_value = $collection->lc_value ?? 0;
            $max_current_distribution = $lc_sc_value - $prevCumulativeDistribution;
            return [
                'primary_master_contract_id' => $collection->id,
                'lc_sc_type' => 'PMC',
                'factory_id' => $collection->beneficiary_id,
                'beneficiary' => $collection->beneficiary->factory_name,
                'buyer_id' => request()->get('buyer_id'),
                'buyer' => request()->get('buyer_id') ? collect($collection->buyer_names)->where('id', request()->get('buyer_id'))->first()['name'] ?? null : null,
                'internal_file_no' => null,
                'year' => null,
                'lc_sc_value' => $collection->contract_value,
                'lc_sc_no' => $collection->unique_id,
                'date' => $collection->ex_cont_issue_date,
                'old_current_distribution' => 0,
                'cumulative_distribution' => $prevCumulativeDistribution,
                'cumulative_distribution_calculation' => $prevCumulativeDistribution,
                'max_current_distribution' => $max_current_distribution,
                'status' => 1,
                'total_use' => $collection->total_use
            ];
        }else {
            $prevCumulativeDistribution = B2BMarginLCDetail::query()
                ->where('sales_contract_id', $collection->id)
                ->sum('current_distribution');
            $lc_sc_value = $collection->contract_value ?? 0;
            $max_current_distribution = $lc_sc_value - $prevCumulativeDistribution;

            return [
                'sales_contract_id' => $collection->id,
                'lc_sc_type' => 'SC',
                'factory_id' => $collection->beneficiary_id,
                'beneficiary' => $collection->beneficiary->factory_name,
                'buyer_id' => request()->get('buyer_id'),
                'buyer' => request()->get('buyer_id') ? collect($collection->buyer_names)->where('id', request()->get('buyer_id'))->first()['name'] ?? null : null,
                'internal_file_no' => $collection->internal_file_no,
                'year' => $collection->year,
                'lc_sc_value' => $collection->contract_value,
                'lc_sc_no' => $collection->contract_number,
                'date' => $collection->contract_date,
                'old_current_distribution' => 0,
                'cumulative_distribution' => $prevCumulativeDistribution,
                'cumulative_distribution_calculation' => $prevCumulativeDistribution,
                'max_current_distribution' => $max_current_distribution,
                'status' => 1,
                'total_use' => $collection->total_use
            ];
        }
    }
}
