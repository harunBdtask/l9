<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use SkylarkSoft\GoRMG\SystemSettings\Models\CostingTemplate;

class TemplateApiController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $templates = CostingTemplate::where("type", $request->type)
                ->where("factory_id", $request->factory)
                ->where("buyer_id", $request->buyer)
                ->whereNotNull('template_name')
                ->get();

            return response()->json($templates, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), $exception->getCode());
        }
    }
}
