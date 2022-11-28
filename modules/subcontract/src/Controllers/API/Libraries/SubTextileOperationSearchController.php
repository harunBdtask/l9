<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\API\Libraries;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Subcontract\Filters\Filter;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubTextileOperation;
use Symfony\Component\HttpFoundation\Response;

class SubTextileOperationSearchController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            $search = $request->search ?? null;
            $name = $request->name ?? null;
            $data = SubTextileOperation::query()
                ->when($search ?? $name, Filter::applyFilter('name', $search ?? $name))
                ->get()
                ->map(function ($operation) {
                    return [
                        'id' => $operation->id,
                        'text' => $operation->name,
                        'name' => $operation->name,
                        'material_popup_status' => $operation->material_popup_status,
                    ];
                });
            $status = Response::HTTP_OK;
            $message = \SUCCESS_MSG;
        } catch (Exception $e) {
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
            $message = \SOMETHING_WENT_WRONG;
            $errors = $e->getMessage();
        }

        return response()->json([
            'data' => $data ?? null,
            'status' => $status,
            'message' => $message,
            'error' => $errors ?? null,
        ], $status);
    }
}
