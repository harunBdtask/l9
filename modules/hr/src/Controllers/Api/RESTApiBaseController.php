<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RESTApiBaseController extends Controller
{
    /**
     * @param array $data
     * @param null $message
     * @return JsonResponse
     */
    public function jsonSuccess($data, $message = null)
    {
        return response()->json(array_merge(
            ['success' => true, 'message' => $message],
            $data
        ));
    }

    public function jsonFailed($data, $message = null)
    {
        return response()->json(array_merge(
            ['success' => false, 'message' => $message],
            $data
        ));
    }
}
