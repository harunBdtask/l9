<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\ProTracker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail;
use SkylarkSoft\GoRMG\Merchandising\Services\ProTracker\ProTrackerDataService;

class ProTrackerDataController extends Controller
{
    /**
     * @var ProTrackerDataService
     */
    private $proTrackerDataService;

    public function __construct(ProTrackerDataService $proTrackerDataService)
    {
        $this->proTrackerDataService = $proTrackerDataService;
    }

    /**
     * @param $poId
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function save($poId): \Illuminate\Http\JsonResponse
    {
        try {
            $purchaseOrderDetails = $this->proTrackerDataService->designDesireData($this->proTrackerDataService->getDesireData($poId)->poDetails);
            DB::beginTransaction();
            $this->proTrackerDataService->deleteDesireData($poId);
            PurchaseOrderDetail::query()->insert($purchaseOrderDetails);
            DB::commit();

            return response()->json([
                'message' => 'Data Inserted Successfully',
                'data' => $purchaseOrderDetails,
            ], Response::HTTP_ACCEPTED);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
