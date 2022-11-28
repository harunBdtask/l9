<?php

namespace SkylarkSoft\GoRMG\Sample\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use SkylarkSoft\GoRMG\Sample\Models\SampleOrderRequisition;
use SkylarkSoft\GoRMG\Sample\Models\SampleOrderRequisitionDetails;
use Symfony\Component\HttpFoundation\Response;

class SampleOrderRequisitionDetailsController extends Controller
{
    public function store(SampleOrderRequisition $sampleOrderRequisition, Request $request)
    {
        try {
            DB::beginTransaction();
            foreach ($request->items as $item) {
                if ($id = $item['id']) {
                    $sampleOrderRequisition->details()->find($id)->update($item);

                    continue;
                }
                $sampleOrderRequisition->details()->create($item);
            }
            $sampleOrderRequisition->update(['requis_details_cal' => $request->total_calculation]);
            DB::commit();

            return response()->json(['message' => 'Data Stored Successfully!', 'samples' => $sampleOrderRequisition->details]);
        } catch (\Throwable $e) {
            dd($e);

            return response()->json(['message' => 'Something Went Wrong!'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function delete(SampleOrderRequisitionDetails $sampleOrderRequisitionDetails, Request $request)
    {
        try {
            $sampleOrderRequisition = SampleOrderRequisition::find($sampleOrderRequisitionDetails->sample_order_requisition_id);
            $sampleOrderRequisition->update(['requis_details_cal' => $request->total_calculation]);
            $this->deleteImage($sampleOrderRequisitionDetails->image_path);
            $sampleOrderRequisitionDetails->delete();

            return response()->json(['message' => 'Successfully Deleted'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something Went Wrong!'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function deleteSingleImage(SampleOrderRequisitionDetails $sampleOrderRequisitionDetails)
    {
        $this->deleteImage($sampleOrderRequisitionDetails->image_path);
        $sampleOrderRequisitionDetails->image_path = null;
        $sampleOrderRequisitionDetails->save();
    }

    private function deleteImage($path)
    {
        if (Storage::exists($path)) {
            Storage::delete($path);
        }
    }
}
