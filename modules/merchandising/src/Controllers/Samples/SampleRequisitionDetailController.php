<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Samples;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PharIo\Version\Exception;
use SkylarkSoft\GoRMG\Merchandising\Models\Samples\SampleRequisition;
use SkylarkSoft\GoRMG\Merchandising\Models\Samples\SampleRequisitionDetail;
use Symfony\Component\HttpFoundation\Response;

class SampleRequisitionDetailController extends Controller
{
    public function store(SampleRequisition $sampleRequisition, Request $request): JsonResponse
    {

        $request->validate([
            '*.sample_id'       => 'required',
            '*.gmts_item_id'    => 'required',
            '*.gmts_colors_id'  => 'required|array|min:1',
            '*.required_qty'    => 'required|numeric',
            '*.submission_date' => 'required|date',
            '*.delivery_date'   => 'required|date',
            '*.calculation'     => 'required',
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->all() as $item) {
                if ( $id = $item['id'] ) {
                    $sampleRequisition->details()->find($id)->update($item);

                    continue;
                }

                $sampleRequisition->details()->create($item);
            }

            DB::commit();

            return response()->json(['message' => 'Data Stored Successfully!', 'samples' => $sampleRequisition->details]);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Something Went Wrong!'], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @param SampleRequisition $sampleRequisition
     * @return JsonResponse
     */
    public function details(SampleRequisition $sampleRequisition): JsonResponse
    {
        $details = $sampleRequisition->details()->get();

        return response()->json($details);
    }

    public function delete(SampleRequisitionDetail $sampleRequisitionDetail): JsonResponse
    {
        try {
            $this->deleteImage($sampleRequisitionDetail->image_path);
            $sampleRequisitionDetail->delete();
            return response()->json('Deleted', Response::HTTP_OK);
        }
        catch (\Throwable $e) {
            return response()->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function deleteSingleImage(SampleRequisitionDetail $sampleRequisitionDetail)
    {
        $this->deleteImage($sampleRequisitionDetail->image_path);
        $sampleRequisitionDetail->image_path = null;
        $sampleRequisitionDetail->save();
    }

    private function deleteImage($path)
    {
        if (Storage::exists($path)) {
            Storage::delete($path);
        }
    }
}
