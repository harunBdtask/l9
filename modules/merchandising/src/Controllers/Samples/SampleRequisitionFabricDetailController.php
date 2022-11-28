<?php


namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Samples;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Merchandising\Models\Samples\SampleRequisition;
use SkylarkSoft\GoRMG\Merchandising\Models\Samples\SampleRequisitionFabricDetail;
use SkylarkSoft\GoRMG\Merchandising\Services\FabricDescriptionService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsSample;
use SkylarkSoft\GoRMG\SystemSettings\Services\DiaTypesService;
use SkylarkSoft\GoRMG\SystemSettings\Services\FabricSourceService;
use Symfony\Component\HttpFoundation\Response;

class SampleRequisitionFabricDetailController extends Controller
{
    /**
     * @param SampleRequisition $requisition
     * @param Request $request
     * @return JsonResponse
     */
    public function store(SampleRequisition $requisition, Request $request): JsonResponse
    {

        $request->validate([
            'sample_id' => 'required|array|min:1',
            'gmts_item_id' => 'required',
            'body_part_id' => 'required',
            'body_part_type' => 'required',
            'fabric_nature_id' => 'required',
            'color_type_id' => 'required',
            'fabric_description_id' => 'required',
            'fabric_source_id' => 'required',
            'dia_type_id' => 'required',
            'gsm' => 'required',
            'gmts_colors_id' => 'required|array|min:1',
            'uom_id' => 'required',
            'req_qty' => 'required',
            'total_qty' => 'required',
            'total_amount' => 'required',
        ]);

        try {
            if ($id = $request->input('id')) {
                $requisition->fabrics()->find($id)->update($request->except('id'));

                return response()->json(['message' => 'Successfully Updated!']);
            }

            $requisition->fabrics()->create($request->all());

            return response()->json(['message' => 'Successfully Saved!']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something Went Wrong!'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function details(SampleRequisition $requisition): JsonResponse
    {
        $fabrics = $requisition->fabrics()->with([
            'gmtsItem:id,name',
            'bodyPart:id,name',
            'fabricNature:id,name',
            'colorType:id,color_types',
        ])->get()->map(function ($fabric) {
            $sensitivityValue = '';

            if ($fabric['sensitivity'] == 1) {
                $sensitivityValue = 'As Per Gmts Color';
            }

            if ($fabric['sensitivity'] == 1) {
                $sensitivityValue = 'Contrast Color';
            }

            $fabric['fabric_description'] = FabricDescriptionService::description($fabric['fabric_description_id']);
            $fabric['fabric_source'] = FabricSourceService::get($fabric['fabric_source_id']);
            $fabric['dia_type'] = DiaTypesService::get($fabric['dia_type_id']);
            $fabric['colors'] = Color::whereIn('id', $fabric['gmts_colors_id'])->pluck('name')->implode(', ');
            $fabric['sensitivity_value'] = $sensitivityValue;
            $fabric['samples_comma_separated'] = GarmentsSample::whereIn('id', $fabric['sample_id'])->pluck('name')->implode(', ');
            $fabric['uom_value'] = '';

            return $fabric;
        });

        return response()->json($fabrics);
    }

    public function deleteFabric(SampleRequisitionFabricDetail $fabricDetail): JsonResponse
    {
        $this->deleteImage($fabricDetail->img_src);
        $fabricDetail->delete();

        return response()->json(['message' => 'Successfully Deleted']);
    }

    public function fabricCostingFromBudget(Request $request)
    {
        try {
            $styleName = $request->input('style_name');

            $budget = Budget::query()->with('fabricCosting')
                ->where('style_name', $styleName)
                ->firstOrFail();

            $fabricCosting = $budget->fabricCosting['details']['details'];

            $formatFabricsCosting = collect($fabricCosting['fabricForm'])->map(function ($collection) {
                $greyConsCalculation = $collection['greyConsForm']['calculation'];
                return [
                    'gmts_item_id' => $collection['garment_item_id'] ?? null,
                    'gmts_item_name' => $collection['garment_item_name'] ?? null,
                    'gmts_colors_id' => [],
                    'body_part_id' => $collection['body_part_id'] ?? null,
                    'body_part_value' => $collection['body_part_value'] ?? null,
                    'body_part_type' => $collection['body_part_type'] ?? null,
                    'fabric_nature_id' => $collection['fabric_nature_id'] ?? null,
                    'fabric_nature_value' => $collection['fabric_nature_value'] ?? null,
                    'color_type_id' => $collection['color_type_id'] ?? null,
                    'color_type_value' => $collection['color_type_value'] ?? null,
                    'sensitivity' => $collection['color_and_size_sensitive'] ?? null,
                    'fabric_description_value' => $collection['fabric_composition_value'] ?? null,
                    'fabric_description_id' => $collection['fabric_composition_id'] ?? null,
                    'fabric_source_id' => $collection['fabric_source'] ?? null,
                    'fabric_source_value' => $collection['fabric_source_value'] ?? null,
                    'dia_type_id' => $collection['dia_type'] ?? null,
                    'dia_type_value' => $collection['dia_type_value'] ?? null,
                    'gsm' => $collection['gsm'] ?? null,
                    'uom_id' => $collection['uom'] ?? null,
                    'rate' => $greyConsCalculation['rate_avg'] ?? null,
                    'avg_grey_qty' => $greyConsCalculation['qty_avg'] ?? null,
                    'req_qty' => 0,
                    'total_qty' => $greyConsCalculation['qty_sum'] ?? null,
                    'total_amount' => $greyConsCalculation['total_amount_sum'] ?? null,
                    'calculation' => [
                        'total_finish_qty' => $greyConsCalculation['finish_cons_sum'],
                        'total_grey_qty' => $greyConsCalculation['qty_sum'],
                        'avg_rate' => $greyConsCalculation['rate_avg'],
                        'total_amount' => $greyConsCalculation['total_amount_sum'],
                        'avg_finish_qty' => $greyConsCalculation['finish_cons_avg'],
                        'avg_grey_qty' => $greyConsCalculation['qty_avg'],
                        'avg_amount' => $greyConsCalculation['total_amount_avg'],
                    ]
                ];
            });

            return response()->json($formatFabricsCosting, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteImageFromDb(SampleRequisitionFabricDetail $fabricDetail)
    {
        $this->deleteImage($fabricDetail->img_src);
        $fabricDetail->img_src = null;
        $fabricDetail->save();
    }

    private function deleteImage($path)
    {
        if (Storage::exists($path)) {
            Storage::delete($path);
        }
    }
}
