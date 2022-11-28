<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API\YarnReceive;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\Inventory\Models\YarnDateWiseStockSummary;
use SkylarkSoft\GoRMG\Inventory\Services\YarnReceive\YarnReceiveStockService;
use SkylarkSoft\GoRMG\Inventory\Services\YarnReceiveBasisBreakDownService;
use SkylarkSoft\GoRMG\Inventory\Services\YarnStockSummaryService;
use SkylarkSoft\GoRMG\SystemSettings\Services\YarnStoreApprovalMaintainService;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Inventory\Models\YarnReceive;
use SkylarkSoft\GoRMG\Inventory\Models\YarnStockSummary;
use SkylarkSoft\GoRMG\Inventory\Models\YarnReceiveDetail;
use SkylarkSoft\GoRMG\Inventory\Requests\YarnReceiveDetailsFormRequest;
use Throwable;

class YarnReceiveDetailsController extends Controller
{
    /**
     * @throws Throwable
     */
    public function store(YarnReceiveDetailsFormRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $stockService = new YarnReceiveStockService();
            $approveMaintain = YarnStoreApprovalMaintainService::getApprovalMaintainStatus();

            if ($request->input('id')) {
                $formData = $request->except(['product_code', 'uom_id', 'yarn_lot', 'store_id', 'yarn_color', 'yarn_brand', 'yarn_type_id', 'yarn_count_id', 'yarn_composition_id']);
                $yarnReceiveDetails = YarnReceiveDetail::query()
                    ->with('yarnReceiveToTouch')
                    ->find($request->input('id'));

                if ($approveMaintain != 1 || ($yarnReceiveDetails->yarnReceiveToTouch->is_approve ?? '') != 1) {
                    $stockService->updated($yarnReceiveDetails);
                }
                $yarnReceiveDetails->update($formData);
            }
            else {
                $formData = $request->except(['supplier', 'uom', 'type', 'yarn_count', 'composition']);
                $yarnReceiveDetails = YarnReceiveDetail::query()->create($formData);
                if ($approveMaintain != 1) {
                    $stockService->created($yarnReceiveDetails);
                }
            }

            DB::commit();
            return response()->json($yarnReceiveDetails, Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($yarn_receive_id): JsonResponse
    {
        try {
            YarnReceive::query()
                ->withoutGlobalScope('approvalMaintain')
                ->findOrFail($yarn_receive_id);
            $yarnReceiveDetail = YarnReceiveDetail::query()->with([
                'supplier', 'uom', 'rack', 'bin', 'type', 'room', 'shelf', 'floor', 'yarn_count', 'composition',
            ])->where('yarn_receive_id', $yarn_receive_id)->orderByDesc('id')->get();
            return response()->json($yarnReceiveDetail, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function edit(YarnReceive $yarnReceiveDetails): JsonResponse
    {
        try {
            return response()->json($yarnReceiveDetails, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    protected function data($yarnReceiveDetails): array
    {
        $yarnReceive = YarnReceive::query()
            ->withoutGlobalScope('approvalMaintain')
            ->first($yarnReceiveDetails->yarn_receive_id);

        return [
            "breakdown" => (new YarnReceiveBasisBreakDownService($yarnReceive->receive_basis, $yarnReceive->receive_basis_id))->output(),
        ];
    }

    /**
     * @throws Throwable
     */
    protected function destroy($id): JsonResponse
    {
        try {
            $yarnReceiveDetails = YarnReceiveDetail::query()->find($id);
            $yarnStockSummary = (new YarnStockSummaryService())->summary($yarnReceiveDetails);

            if ($yarnReceiveDetails->receive_qty <= $yarnStockSummary->balance) {
                DB::beginTransaction();

                (new YarnReceiveStockService())->deleted($yarnReceiveDetails);

                $yarnReceiveDetails->delete();
                DB::commit();
                return response()->json('Delete Item Success', Response::HTTP_OK);
            }
            return response()->json('This Lot Use Somewhere', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
