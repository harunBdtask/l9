<?php

namespace SkylarkSoft\GoRMG\Approval\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Approval\Models\Approval;
use SkylarkSoft\GoRMG\Approval\Services\DyesChemicalStoreApprovalService;
use SkylarkSoft\GoRMG\DyesStore\Models\DyesChemicalsReceive;
use SkylarkSoft\GoRMG\DyesStore\Models\DyesChemicalTransaction;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class DyesChemicalStoreApprovalController extends Controller
{
    public function index()
    {
        return view('approval::approvals.modules.dyes-chemical-store-approval');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        try {
            $data = DyesChemicalStoreApprovalService::for(Approval::DYES_CHEMICAL_STORE_APPROVAL)
                ->setRequest($request)
                ->response();

            return response()->json($data, Response::HTTP_OK);
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
            $data = DyesChemicalStoreApprovalService::for(Approval::DYES_CHEMICAL_STORE_APPROVAL)
                ->setRequest($request)
                ->store();
            DB::commit();

            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $exception) {
            if ($exception->getCode() == 422) {
                return response()->json('Can not unapproved this item!', Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function unApprovedRequests(Request $request): JsonResponse
    {
        $data = DyesChemicalStoreApprovalService::for(Approval::DYES_CHEMICAL_STORE_APPROVAL)
            ->setRequest($request)
            ->getUnapprovedData();

        return response()->json($data);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function unApprove(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $query = DyesChemicalsReceive::query()
                ->whereIn('id', $request->get('submitId'));

            $stockQuery = DyesChemicalTransaction::query()->whereIn('dyes_chemical_receive_id', $request->get('submitId'));
            $stockData = $stockQuery->get();

            if ($stockData->whereNotNull('dyes_chemical_issue_id')->count() > 0) {
                return response()->json(['message' => 'Can not unapproved this item!'], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $stockQuery->delete();

            $query->update([
                'readonly' => 1,
                'is_approve' => null,
                'step' => 0,
                'ready_to_approve' => 0,
                'approved_by' => '[]',
                'un_approve_request' => null,
                'approve_date' => null
            ]);

            DB::commit();
            return response()->json(['message' => 'Successfully Updated!'], Response::HTTP_OK);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
