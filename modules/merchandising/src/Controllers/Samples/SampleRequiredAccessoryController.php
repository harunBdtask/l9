<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Samples;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Merchandising\Models\Samples\SampleRequiredAccessory;
use SkylarkSoft\GoRMG\Merchandising\Models\Samples\SampleRequisition;
use Symfony\Component\HttpFoundation\Response;

class SampleRequiredAccessoryController extends Controller
{

    private $message;

    public function list(SampleRequisition $requisition): JsonResponse
    {
        $accessories = $requisition
            ->accessories()
            ->with(['sample', 'garmentsItem', 'item', 'uom'])
            ->get();

        return response()->json($accessories);
    }

    public function store(SampleRequisition $requisition, Request $request): JsonResponse
    {
        $this->validateRequest($request);

        try {
            if ($id = $request->input('id')) {
                $requisition->accessories()->find($id)->update($request->toArray());
                $this->message = S_UPDATE_MSG;
            } else {
                $requisition->accessories()->create($request->toArray());
                $this->message = S_SAVE_MSG;
            }

            return response()->json(['message' => $this->message]);
        } catch (Exception $e) {
            $response = ['message' => $this->message, 'errMsg' => $e->getMessage()];
            return response()->json($response, Response::HTTP_BAD_REQUEST);
        }
    }

    private function validateRequest(Request $request)
    {
        $request->validate([
            'sample_id' => 'required',
            'gmts_item_id' => 'required',
            'item_id' => 'required',
            'brand_sup_ref' => 'nullable',
            'description' => 'nullable',
            'rate' => 'required',
            'req_qty' => 'required',
            'total_qty' => 'required',
            'amount' => 'required',
            'uom_id' => 'required',
            'uom_value' => 'required',
            'remarks' => 'nullable',
            'image' => 'nullable'
        ]);
    }

    public function delete(SampleRequiredAccessory $accessory): JsonResponse
    {
        $accessory->delete();
        return response()->json(['message' => S_DEL_MSG]);
    }

    public function trimsCostingFromBudget(Request $request): JsonResponse
    {
        try {
            $styleName = $request->input('style_name');

            $budget = Budget::query()->with('trimCosting')
                ->where('style_name', $styleName)
                ->first();

            $trimsCosting = $budget->trimCosting['details']['details'];

            $formatTrimsCosting = collect($trimsCosting)->map(function ($collection) {
                return [
                    'gmts_item_id' => $collection['gmts_item_id'] ?? null,
                    'gmts_item_name' => $collection['gmts_item_name'] ?? null,
                    'item_id' => $collection['group_id'] ?? null,
                    'item_name' => $collection['group_name'] ?? null,
                    'description' => $collection['description'] ?? null,
                    'brand_sup_ref' => $collection['brand_id'] ?? null,
                    'brand_sup_ref_value' => $collection['brand_value'] ?? null,
                    'uom_id' => $collection['cons_uom_id'] ?? null,
                    'uom_value' => $collection['cons_uom_value'] ?? null,
                    'req_qty' => $collection['cons_gmts'] ?? null,
                    'trims_rate' => $collection['rate'] ?? null,
                    'trims_amount' => $collection['amount'] ?? null,
                    'trims_total_qty' => $collection['total_quantity'] ?? null,
                    'trims_total_amount' => $collection['total_amount'] ?? null,
                    'total_qty' => null,
                    'amount' => null,
                    'rate' => null,
                    'remarks' => null,
                    'image' => null,
                ];
            });
            return response()->json($formatTrimsCosting, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteImageFromDb(SampleRequiredAccessory $requiredAccessory)
    {
        $this->deleteImage($requiredAccessory->image);
        $requiredAccessory->image = null;
        $requiredAccessory->save();
    }

    private function deleteImage($path)
    {
        if (Storage::exists($path)) {
            Storage::delete($path);
        }
    }
}
