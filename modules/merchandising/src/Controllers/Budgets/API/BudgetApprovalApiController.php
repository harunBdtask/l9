<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Budgets\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Approval\Controllers\BudgetApprovalController;
use SkylarkSoft\GoRMG\Approval\Models\Approval;
use SkylarkSoft\GoRMG\Approval\Services\BudgetApprovalService;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Merchandising\Models\PriceQuotation;
use Symfony\Component\HttpFoundation\Response;

class BudgetApprovalApiController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function getUnapprovedData(Request $request)
    {
        $unApprovedData = BudgetApprovalService::for(Approval::BUDGET_APPROVAL)
            ->setRequest($request)
            ->setBuyer($request->get('buyer'))
            ->getUnapprovedData();

        return response()->json($unApprovedData);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function updateApprovedStatus(Request $request): JsonResponse
    {
        try {
            Budget::query()->whereIn('id', $request)->update([
                'is_approve' => null,
                'step' => 0
            ]);

            return response()->json(['message' => 'Successfully Updated!'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something Went Wrong!'], Response::HTTP_BAD_REQUEST);
        }
    }
}
