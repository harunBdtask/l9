<?php

namespace SkylarkSoft\GoRMG\Procurement\Controllers\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;

use SkylarkSoft\GoRMG\Procurement\Models\ProcurementRequisition;
use SkylarkSoft\GoRMG\Procurement\Models\ProcurementRequisitionDetail;
use SkylarkSoft\GoRMG\Procurement\Models\ProcureQuotation;
use SkylarkSoft\GoRMG\SystemSettings\Models\Department;
use SkylarkSoft\GoRMG\SystemSettings\Models\ItemGroup;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;
use Symfony\Component\HttpFoundation\Response;

class CommonApiController extends Controller
{
    public function getDepartments(): JsonResponse
    {
        try {
            $departments = Department::query()->get(['id', 'department_name as text']);

            return response()->json([
                'message' => 'Fetch departments successfully',
                'data' => $departments,
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getUsers(): JsonResponse
    {
        try {
            $users = User::query()->get(['id', 'screen_name as text']);

            return response()->json([
                'message' => 'Fetch Users successfully',
                'data' => $users,
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getItemGroups(): JsonResponse
    {
        try {
            $items = ItemGroup::orderBy('item_group', 'asc')->get(['id', 'item_group as text']);

            return response()->json([
                'message' => 'Fetch Items successfully',
                'data' => $items,
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getUoms(): JsonResponse
    {
        try {
            $uoms = UnitOfMeasurement::query()->get(['id', 'unit_of_measurement as text']);

            return response()->json([
                'message' => 'Fetch Users successfully',
                'data' => $uoms,
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getSuppliers(): JsonResponse
    {
        try {
            $suppliers = Supplier::query()->get(['id', 'name as text']);

            return response()->json([
                'message' => 'Fetch supplier successfully',
                'data' => $suppliers,
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function fetchRequisitions(): JsonResponse
    {
        try {
            $data = ProcurementRequisition::query()->get(['requisition_uid as text','id']);

            return response()->json([
                'message' => 'Fetch Requisitions successfully',
                'data' => $data,
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function fetchRequisitionItems($id): JsonResponse
    {
        try {
            $data = ProcurementRequisitionDetail::query()->with('item:id,item_group')->where('procurement_requisition_id', $id)->get();
            $items = $data ? collect($data)->pluck('item')->map(function ($item) {
                return [
                    'id' => $item->id,
                    'text' => $item->item_group,
                ];
            }) : [];

            return response()->json([
                'message' => 'Fetch requisition items successfully',
                'data' => $items,
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function fetchQuotationDescription($supplier_id, $item_id): JsonResponse
    {
        try {
            $data = ProcureQuotation::query()->where('supplier_id', $supplier_id)->where('item_id', $item_id)->get(['*','item_description as text']);

            return response()->json([
                'message' => 'Fetch Quotation Description successfully',
                'data' => $data,
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
