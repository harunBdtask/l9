<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\API\SubTextileAPIs;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Subcontract\Filters\Filter;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use Symfony\Component\HttpFoundation\Response;

class SubTextilePartiesSearchController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $buyers = Buyer::query()
                ->where('party_type', 'Subcontract')
                ->factoryFilter()
                ->get(['id', 'name as text']);

            return response()->json([
                'data' => $buyers ?? [],
                'status' => Response::HTTP_OK,
                'message' => \SUCCESS_MSG,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => \SOMETHING_WENT_WRONG,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

//    public function __invoke(Request $request)
//    {
//        try {
//            $search = $request->search ?? null;
//            $factoryId = $request->factory_id ?? null;
//
//            $ids = $this->fetchSupplierIds($factoryId);
//
//            $data = null;
//            if ($ids && \is_array($ids) && count($ids)) {
//                $data = Supplier::query()
//                    ->withoutGlobalScope('factoryId')
//                    ->when($search, Filter::applyFilter('name', $search))
//                    ->whereIn('id', $ids)
//                    ->get()
//                    ->map(function ($supplier) {
//                        return [
//                            'id' => $supplier->id,
//                            'text' => $supplier->name,
//                            'name' => $supplier->name,
//                            'party_type' => $supplier->party_type,
//                        ];
//                    });
//            }
//            $status = Response::HTTP_OK;
//            $message = \SUCCESS_MSG;
//        } catch (Exception $e) {
//            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
//            $message = \SOMETHING_WENT_WRONG;
//            $errors = $e->getMessage();
//        }
//
//        return response()->json([
//            'data' => $data ?? null,
//            'status' => $status,
//            'message' => $message,
//            'error' => $errors ?? null
//        ], $status);
//    }
//
//    private function fetchSupplierIds($factoryId = null)
//    {
//        $subcontractPartyTypes = [
//            'aop_subcontract' => 'AOP Subcontract',
//            'dyeing_finishing_subcontract' => 'Dyeing/Finishing Subcontract',
//            'fabric_washing_subcontract' => 'Fabric Washing Subcontract',
//            'grey_fabric_service_subcontract' => 'Grey Fabric Service Subcontract',
//            'knit_subcontract' => 'Knit Subcontract',
//        ];
//
//        $aop_ids = $this->query($subcontractPartyTypes['aop_subcontract'], $factoryId);
//        $dyeing_finishing_ids = $this->query($subcontractPartyTypes['dyeing_finishing_subcontract'], $factoryId);
//        $fabric_washing_ids = $this->query($subcontractPartyTypes['fabric_washing_subcontract'], $factoryId);
//        $grey_fabric_service_ids = $this->query($subcontractPartyTypes['grey_fabric_service_subcontract'], $factoryId);
//        $knit_subcontract_ids = $this->query($subcontractPartyTypes['knit_subcontract'], $factoryId);
//
//        return array_unique(array_merge($aop_ids, $dyeing_finishing_ids, $fabric_washing_ids, $grey_fabric_service_ids, $knit_subcontract_ids));
//    }
//
//    private function query($subcontractPartyType, $factoryId)
//    {
//        return Supplier::query()->withoutGlobalScope('factoryId')
//            ->whereRaw('FIND_IN_SET("' . $subcontractPartyType . '", party_type)')
//            ->when($factoryId, Filter::applyFilter('factory_id', $factoryId))
//            ->pluck('id')
//            ->toArray();
//    }
}
