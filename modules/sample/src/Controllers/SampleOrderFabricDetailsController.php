<?php

namespace SkylarkSoft\GoRMG\Sample\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Sample\Models\SampleOrderFabricDetails;
use SkylarkSoft\GoRMG\Sample\Models\SampleOrderRequisition;
use Symfony\Component\HttpFoundation\Response;

class SampleOrderFabricDetailsController extends Controller
{
    public function store(SampleOrderRequisition $sampleOrderRequisition, Request $request)
    {
        try {
            DB::beginTransaction();
            if (isset($request->fabricMain['id'])) {
                $fabricMainId = $request->fabricMain['id'];
                $sampleOrderRequisition->fabrics()->find($fabricMainId)->update($request->fabricMain);
            } else {
                $fabricMain = $sampleOrderRequisition->fabrics()->create($request->fabricMain);
                $fabricMainId = $fabricMain->id;
            }
            foreach ($request->items as $item) {
                $item['sample_order_fabric_id'] = $fabricMainId;
                if (isset($item['id'])) {
                    $sampleOrderRequisition->fabricDetails()->find($item['id'])->update($item);

                    continue;
                }
                $sampleOrderRequisition->fabricDetails()->create($item);
            }
            $sampleOrderRequisition->update(['fabric_details_cal' => $request->total_calculation]);
            DB::commit();
            $data = [
                'fabrics' => $sampleOrderRequisition->fabrics,
                'fabricDetails' => $sampleOrderRequisition->fabricDetails,
            ];

            return response()->json(['message' => 'Data Stored Successfully!', 'data' => $data], Response::HTTP_OK);
        } catch (\Throwable $e) {
            dd($e);

            return response()->json(['message' => 'Something Went Wrong!'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function delete(SampleOrderFabricDetails $sampleOrderFabricDetails, Request $request)
    {
        try {
            $sampleOrderRequisition = SampleOrderRequisition::find($sampleOrderFabricDetails->sample_order_requisition_id);
            $sampleOrderRequisition->update(['fabric_details_cal' => $request->total_calculation]);
            $sampleOrderFabricDetails->delete();

            return response()->json(['message' => 'Successfully Deleted'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something Went Wrong!'], Response::HTTP_BAD_REQUEST);
        }
    }
}
