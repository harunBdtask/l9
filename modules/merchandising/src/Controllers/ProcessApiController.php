<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use SkylarkSoft\GoRMG\SystemSettings\Models\Process;

class ProcessApiController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(): \Illuminate\Http\JsonResponse
    {
        try {
            $process = Process::get();

            return response()->json($process, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), $exception->getCode());
        }
    }
}
