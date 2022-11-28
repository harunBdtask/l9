<?php

namespace SkylarkSoft\GoRMG\Approval\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Approval\Models\Approval;
use SkylarkSoft\GoRMG\Approval\Services\BudgetApprovalService;
use SkylarkSoft\GoRMG\Approval\Services\PriorityService;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Merchandising\Models\PriceQuotation;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class BudgetApprovalController extends Controller
{

    const PAGE_NAME = 'Budget Approval';

    public function index()
    {
        return view('approval::approvals.modules.budgetApproval');
    }

    public function create(Request $request): JsonResponse
    {
        try {
            $budget = BudgetApprovalService::for(Approval::BUDGET_APPROVAL)
                ->setRequest($request)
                ->setBuyer($request->get('buyer'))
                ->response();

            $response = [
                'data' => $budget,
                'status' => Response::HTTP_OK,
                'message' => 'Budget fetched successfully',
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }


    }

    /**
     * @throws Throwable
     */
    public function store(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $budget = BudgetApprovalService::for(Approval::BUDGET_APPROVAL)
                ->setRequest($request)
                ->setBuyer($request->get('buyer'))
                ->store();

            $response = [
                'data' => $budget,
                'status' => Response::HTTP_OK,
                'message' => 'Budget updated successfully',
            ];
            DB::commit();

            return response()->json($request->get('job_no'), Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    public function getBudgetUnique($buyerId)
    {
        try {
            $budgetUnique = Budget::query()->where('buyer_id',$buyerId)->get(['id', 'job_no as text']);
            return response()->json(['data' => $budgetUnique], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getBudgetStyle($buyerId)
    {
        try {
            $budgetStyle = Budget::query()->where('buyer_id',$buyerId)->get(['id', 'style_name as text']);
            return response()->json(['data' => $budgetStyle], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }



    /**
     * @throws \Throwable
     */
    public function cancelAndRework(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $type = $request->get('type');
            $query = Budget::query()->whereIn('job_no', $request->get('job_no'));
            if ($type == 'cancel') {
                $query->update(['cancel_status' => 1]);
            }
            if ($type == 'rework') {
                $query->each(function ($budget) {
                    $budget->update(['rework_status' => !$budget->rework_status]);
                });
            }
            DB::commit();

            return response()->json('Success', Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
