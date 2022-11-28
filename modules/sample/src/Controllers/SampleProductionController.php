<?php

namespace SkylarkSoft\GoRMG\Sample\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Sample\Models\SampleProcessing;
use SkylarkSoft\GoRMG\Sample\Models\SampleProductionDetails;
use Symfony\Component\HttpFoundation\Response;

class SampleProductionController extends Controller
{
    public function store(SampleProcessing $sampleProcessing, Request $request)
    {
        try {
            DB::beginTransaction();
            if (isset($request->productionMain['id'])) {
                $productionMainId = $request->productionMain['id'];
                $sampleProcessing->productions()->find($productionMainId)->update($request->productionMain);
                $message = 'Successfully Updated!';
            } else {
                $productionMain = $sampleProcessing->productions()->create($request->productionMain);
                $productionMainId = $productionMain->id;
                $message = 'Successfully Saved!';
            }
            foreach ($request->items as $item) {
                $item['sample_production_id'] = $productionMainId;
                $item['sample_order_requisition_id'] = $request->productionMain['sample_order_requisition_id'];
                if (isset($item['id'])) {
                    $sampleProcessing->sampleProductionDetails()->find($item['id'])->update($item);

                    continue;
                }
                $sampleProcessing->sampleProductionDetails()->create($item);
            }
            $sampleProcessing->productions()->update(['total_calculation' => $request->total_calculation]);
            DB::commit();
            $data = [
                'productions' => $sampleProcessing->productions,
                'sampleProductionDetails' => $sampleProcessing->sampleProductionDetails,
            ];

            return response()->json(['message' => $message, 'data' => $data], Response::HTTP_OK);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Something Went Wrong!'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function delete(SampleProductionDetails $sampleProductionDetails, Request $request)
    {
        try {
            DB::beginTransaction();
            $mainData = SampleProcessing::find($sampleProductionDetails->sample_processing_id);
            $mainData->productions()->update(['total_calculation' => $request->total_calculation]);
            $sampleProductionDetails->delete();
            DB::commit();

            return response()->json(['message' => 'Successfully Deleted'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something Went Wrong!'], Response::HTTP_BAD_REQUEST);
        }
    }
}
