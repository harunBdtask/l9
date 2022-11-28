<?php


namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Budgets\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Merchandising\Models\Budgets\BudgetCostingTemplate;
use Symfony\Component\HttpFoundation\Response;

class BudgetTemplateApiController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $templates = BudgetCostingTemplate::query()
                ->where("type", $request->get('type'))
                ->where("factory_id", factoryId())
                ->where("buyer_id", $request->get('buyer'))
                ->get();

            return response()->json($templates, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), $exception->getCode());
        }
    }
}
