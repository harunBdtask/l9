<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\API\Libraries;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Subcontract\Filters\Filter;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubTextileProcess;
use Symfony\Component\HttpFoundation\Response;

class SubTextileProcessSearchController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            $search = $request->search ?? null;
            $factory_id = $request->factory_id ?? null;
            $sub_textile_operation_id = $request->sub_textile_operation_id ?? null;
            $data = SubTextileProcess::query()
                ->where('status', SubTextileProcess::ACTIVE)
                ->when($factory_id, Filter::applyFilter('factory_id', $factory_id))
                ->when($sub_textile_operation_id, Filter::applyFilter('sub_textile_operation_id', $sub_textile_operation_id))
                ->when($search, Filter::applyFilter('name', $search))
                ->get()
                ->map(function ($process) {
                    return [
                        'id' => $process->id,
                        'text' => $process->name,
                        'name' => $process->name,
                        'price' => $process->price,
                        'status' => $process->status,
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
