<?php

namespace SkylarkSoft\GoRMG\Sample\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Sample\Models\SampleOrderAccessoriesDetails;
use SkylarkSoft\GoRMG\Sample\Models\SampleOrderRequisition;
use Symfony\Component\HttpFoundation\Response;

class SampleOrderAccessoriesDetailsController extends Controller
{
    public function store(SampleOrderRequisition $sampleOrderRequisition, Request $request)
    {
        try {
            DB::beginTransaction();
            foreach ($request->items as $item) {
                if (isset($item['id'])) {
                    $sampleOrderRequisition->accessories()->find($item['id'])->update($item);

                    continue;
                }
                $sampleOrderRequisition->accessories()->create($item);
            }
            $sampleOrderRequisition->update(['accessories_details_cal' => $request->total_calculation]);
            DB::commit();

            return response()->json(['message' => 'Data Stored Successfully!', 'data' => $sampleOrderRequisition->accessories], Response::HTTP_OK);
        } catch (\Throwable $e) {
            dd($e);

            return response()->json(['message' => 'Something Went Wrong!'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function delete(SampleOrderAccessoriesDetails $sampleOrderAccessoriesDetails, Request $request)
    {
        try {
            $sampleOrderRequisition = SampleOrderRequisition::find($sampleOrderAccessoriesDetails->sample_order_requisition_id);
            $sampleOrderRequisition->update(['accessories_details_cal' => $request->total_calculation]);
            $sampleOrderAccessoriesDetails->delete();

            return response()->json(['message' => 'Successfully Deleted'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something Went Wrong!'], Response::HTTP_BAD_REQUEST);
        }
    }
}
