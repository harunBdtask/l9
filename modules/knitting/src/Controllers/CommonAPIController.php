<?php

namespace SkylarkSoft\GoRMG\Knitting\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\Knitting\Models\FabricSalesOrder;
use SkylarkSoft\GoRMG\Knitting\Models\FabricSalesOrderDetail;
use SkylarkSoft\GoRMG\Knitting\Models\KnitCard;
use SkylarkSoft\GoRMG\Knitting\Models\KnitCardYarnDetail;
use SkylarkSoft\GoRMG\Knitting\Models\KnittingProgramColorsQty;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\ColorRange;
use SkylarkSoft\GoRMG\SystemSettings\Models\NewFabricComposition;
use SkylarkSoft\GoRMG\SystemSettings\Models\Process;
use SkylarkSoft\GoRMG\SystemSettings\Models\Size;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnCount;
use SkylarkSoft\GoRMG\SystemSettings\Services\MachineTypeService;
use Symfony\Component\HttpFoundation\Response;

class CommonAPIController extends Controller
{
    public function suppliers(): JsonResponse
    {
        $suppliers = Supplier::query()
            ->where('party_type', 'like', '%Fabric Supplier%')
            ->get(['id', 'name as text']);

        return response()->json($suppliers);
    }

    public function fabricDescriptions(): JsonResponse
    {
        try {
            $fabric_composition_data = NewFabricComposition::with(['newFabricCompositionDetails.yarnComposition'])
                ->get()
                ->map(function ($fabric_composition) {
                    $composition = '';
                    $first_key = $fabric_composition->newFabricCompositionDetails->keys()->first();
                    $last_key = $fabric_composition->newFabricCompositionDetails->keys()->last();
                    $fabric_composition->newFabricCompositionDetails()
                        ->each(function ($fabric_item, $key) use (&$composition, $first_key, $last_key, $fabric_composition) {
                            $composition .= ($key === $first_key) ? "{$fabric_composition->construction} [" : '';
                            $composition .= "{$fabric_item->yarnComposition->yarn_composition} {$fabric_item->percentage}%";
                            $composition .= ($key !== $last_key) ? ', ' : ']';
                        });

                    return [
                        "id" => $fabric_composition->id,
                        "text" => $composition,
                        "gsm" => $fabric_composition->gsm,
                    ];
                });

            return response()->json($fabric_composition_data, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), $exception->getCode());
        }
    }

    public function collarAndCuff($id)
    {
        FabricSalesOrderDetail::query()->where('fabric_sales_order_id', $id)
            ->with('color')
            ->get();
    }

    public function getGmtSize(): JsonResponse
    {
        try {
            $sizes = Size::query()
                ->where('factory_id', factoryId())
                ->select('id', 'name AS text')
                ->get();
            return response()->json($sizes, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getDefaultUOM($uom): JsonResponse
    {
        try {
            $umo = UnitOfMeasurement::query()
                ->where('unit_of_measurement', $uom)
                ->first(['id', 'unit_of_measurement as text']);

            return response()->json($umo, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getBuyerForWithinStatusNo(): JsonResponse
    {

        try {
            $buyerTypeId = Buyer::query()->where('party_type', 'LIKE', "% Buyer %")->get()
                ->pluck('id')->unique()->values()
                ->toArray();

            $buyer = Buyer::query()->whereNotIn('party_type', $buyerTypeId)->get(['id', 'name as text']);

            return response()->json($buyer, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getYarnCounts(): JsonResponse
    {
        try {
            $yarnCounts = YarnCount::query()
                ->where('factory_id', factoryId())
                ->get(['id', 'yarn_count as text']);

            return response()->json($yarnCounts, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function salesOrderNosByBuyer($buyerId): JsonResponse
    {
        try {
            $salesOrderNos = FabricSalesOrder::query()
                ->where('buyer_id', $buyerId)
                ->latest()
                ->get(['id', 'sales_order_no']);

            return response()->json($salesOrderNos, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getDefaultColorRange($colorRange): JsonResponse
    {
        $data = ColorRange::query()->where('name', 'LIKE', "%{$colorRange}%")->first();
        return response()->json($data, Response::HTTP_OK);
    }

    public function getDefaultProcess($process): JsonResponse
    {
        $data = Process::query()
            ->where('process_name', 'LIKE', "%{$process}%")
            ->first();
        return response()->json($data, Response::HTTP_OK);
    }

    public function getProgramQtyInfo($programId, $colorId): JsonResponse
    {
        $programQty = KnittingProgramColorsQty::query()->where('knitting_program_id', $programId)
            ->where('item_color_id', $colorId)
            ->sum('program_qty');

        $assignQty = KnitCard::query()->where('knitting_program_id', $programId)
            ->where('color_id', $colorId)
            ->sum('assign_qty');

        return response()->json([
            'program_qty' => $programQty,
            'knit_card_sum_assign_qty' => $assignQty
        ], Response::HTTP_OK);
    }

    public function getMachineType(): JsonResponse
    {
        $machineType = MachineTypeService::all();
        return response()->json($machineType, Response::HTTP_OK);
    }
}
