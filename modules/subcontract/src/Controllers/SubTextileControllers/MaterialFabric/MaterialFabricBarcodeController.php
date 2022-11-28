<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\SubTextileControllers\MaterialFabric;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreBarcodeDetail;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreReceive;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreReceiveDetails;
use SkylarkSoft\GoRMG\Subcontract\PackageConst;
use SkylarkSoft\GoRMG\Subcontract\Requests\SubTextileRequests\FabricBarcodeDetailFormRequest;
use Symfony\Component\HttpFoundation\Response;

class MaterialFabricBarcodeController extends Controller
{
    public function create(SubGreyStoreReceive $receive)
    {
        $receive->load('receiveDetails');

        $receiveDetails = $receive->getRelation('receiveDetails')->map(function ($collection) {
            if (! count($collection->barcodes)) {
                return [
                    'id' => $collection->id,
                    'factory_id' => $collection->factory_id,
                    'sub_grey_store_receive_id' => $collection->sub_grey_store_receive_id,
                    'sub_grey_store_id' => $collection->sub_grey_store_id,
                    'supplier_id' => $collection->supplier_id,
                    'sub_textile_order_id' => $collection->sub_textile_order_id,
                    'sub_textile_order_detail_id' => $collection->sub_textile_order_detail_id,
                    'grey_required_qty' => $collection->grey_required_qty,
                    'total_roll' => $collection->total_roll,
                    'receive_qty' => $collection->receive_qty,
                    'fabric_description' => $collection->fabric_description,
                    'sub_textile_operation_id' => $collection->sub_textile_operation_id,
                    'sub_textile_operation' => $collection->operation->name,
                    'body_part_id' => $collection->body_part_id,
                    'body_part' => $collection->bodyPart->name,
                    'fabric_composition_id' => $collection->fabric_composition_id,
                    'fabric_type_id' => $collection->fabric_type_id,
                    'fabric_type' => $collection->fabricType->construction_name,
                    'color_id' => $collection->color_id,
                    'color' => $collection->color->name,
                    'ld_no' => $collection->ld_no,
                    'color_type_id' => $collection->color_type_id,
                    'color_type' => $collection->colorType->color_types,
                    'finish_dia' => $collection->finish_dia,
                    'dia_type_id' => $collection->dia_type_id,
                    'dia_type_value' => $collection->dia_type_value['name'],
                    'gsm' => $collection->gsm,
                    'unit_of_measurement_id' => $collection->unit_of_measurement_id,
                    'unit_of_measurement' => $collection->unitOfMeasurement->unit_of_measurement,
                ];
            }

            return null;
        })->filter(function ($collection) {
            return $collection != null;
        })->values();

        return view(PackageConst::VIEW_PATH . 'textile_module.material-fabric.receive.barcode.roll_generator', [
            'receiveDetails' => $receiveDetails,
        ]);
    }

    public function store(FabricBarcodeDetailFormRequest $request): JsonResponse
    {
        try {
            foreach ($request->input('barcode_qty') as $key => $barcodeQty) {
                SubGreyStoreBarcodeDetail::query()->create([
                    "factory_id" => $request->input('factory_id'),
                    "sub_grey_store_receive_id" => $request->input('sub_grey_store_receive_id'),
                    "sub_grey_store_receive_detail_id" => $request->input('sub_grey_store_receive_detail_id'),
                    "sub_grey_store_id" => $request->input('sub_grey_store_id'),
                    "supplier_id" => $request->input('supplier_id'),
                    "sub_textile_order_id" => $request->input('sub_textile_order_id'),
                    "sub_textile_order_detail_id" => $request->input('sub_textile_order_detail_id'),
                    "roll_id" => $key + 1,
                    "barcode_qty" => $barcodeQty,
                ]);
            }

            return response()->json([
                'message' => 'Barcode generated successfully',
                'data' => null,
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function view(SubGreyStoreReceive $receive)
    {
        $receive->load([
            'barcodes',
            'receiveDetails.barcodes.subDyeingOrderDetail.supplier',
            'receiveDetails.barcodes.subDyeingOrderDetail.unitOfMeasurement',
        ]);

        if (count($receive->barcodes) === 0) {
            Session::flash('error', 'Barcodes not generated yet');

            return back();
        }

        return view(PackageConst::VIEW_PATH . 'textile_module.material-fabric.receive.barcode.view', [
            'receive' => $receive,
        ]);
    }

    public function print(SubGreyStoreReceiveDetails $detail)
    {
        $detail->load('barcodes');
        $barcodeStyle = getYarnStoreStickerStyles();
        $barcode_font_size = $barcodeStyle['barcode_font_size'];
        $barcode_width = $barcodeStyle['barcode_width'];
        $barcode_height = $barcodeStyle['barcode_height'];
        $barcode_container_m_top = $barcodeStyle['barcode_container_m_top'];
        $barcode_container_m_left = $barcodeStyle['barcode_container_m_left'];
        $barcode_container_m_right = $barcodeStyle['barcode_container_m_right'];
        $barcode_container_m_bottom = $barcodeStyle['barcode_container_m_bottom'];
        $barcode_container_p_top = $barcodeStyle['barcode_container_p_top'];
        $barcode_container_p_left = $barcodeStyle['barcode_container_p_left'];
        $barcode_container_p_right = $barcodeStyle['barcode_container_p_right'];
        $barcode_container_p_bottom = $barcodeStyle['barcode_container_p_bottom'];

        return view(PackageConst::VIEW_PATH . 'textile_module.material-fabric.receive.barcode.print', [
            'detail' => $detail,
            'barcode_width' => $barcode_width,
            'barcode_height' => $barcode_height,
            'barcode_font_size' => $barcode_font_size,
            'barcode_container_m_top' => $barcode_container_m_top,
            'barcode_container_m_left' => $barcode_container_m_left,
            'barcode_container_m_right' => $barcode_container_m_right,
            'barcode_container_m_bottom' => $barcode_container_m_bottom,
            'barcode_container_p_top' => $barcode_container_p_top,
            'barcode_container_p_left' => $barcode_container_p_left,
            'barcode_container_p_right' => $barcode_container_p_right,
            'barcode_container_p_bottom' => $barcode_container_p_bottom,
        ]);
    }
}
