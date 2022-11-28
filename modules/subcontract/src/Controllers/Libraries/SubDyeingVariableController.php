<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\Libraries;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubcontractVariableSetting;
use SkylarkSoft\GoRMG\Subcontract\Requests\SubTextileRequests\SubDyeingVariableFormRequest;
use Symfony\Component\HttpFoundation\Response;

class SubDyeingVariableController extends Controller
{
    public function index()
    {
        return view('subcontract::libraries.sub_dyeing_variable');
    }

    /**
     * @param SubDyeingVariableFormRequest $request
     * @param SubcontractVariableSetting $subcontractVariableSetting
     * @return JsonResponse
     */
    public function store(SubDyeingVariableFormRequest $request, SubcontractVariableSetting $subcontractVariableSetting): JsonResponse
    {
        try {
            SubcontractVariableSetting::query()->updateOrCreate([
                'factory_id' => $request->input('factory_id'),
            ], $request->all());

            return response()->json([
                'message' => 'Subcontract variable setting stored successfully',
                'data' => $subcontractVariableSetting,
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
