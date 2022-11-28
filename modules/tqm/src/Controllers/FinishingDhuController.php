<?php

namespace SkylarkSoft\GoRMG\TQM\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\TQM\Models\TqmFinishingDhu;
use SkylarkSoft\GoRMG\TQM\Models\TqmFinishingDhuDetails;
use SkylarkSoft\GoRMG\TQM\Requests\FinishingDhuRequest;
use SkylarkSoft\GoRMG\TQM\Services\FinishingDhuDataService;
use Symfony\Component\HttpFoundation\Response;

class FinishingDhuController extends Controller
{
    public function finishingProductionData(FinishingDhuRequest $request): JsonResponse
    {
        try {
            $finishingProductions = (new FinishingDhuDataService($request))->getData();
            return response()->json($finishingProductions, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(FinishingDhuRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            foreach ($request->all() as $data) {
                $criteria = FinishingDhuDataService::setTqmFinishingCriteria($data);
                $criteria['production_date'] = $data['production_date'];

                $finishingDhu = TqmFinishingDhu::query()->updateOrCreate($criteria, $data);

                foreach ($data['details'] as $detail) {
                    $detailFillable = array_merge($detail, FinishingDhuDataService::setTqmFinishingCriteria($finishingDhu));
                    $detailFillable['tqm_finishing_dhu_id'] = $finishingDhu->id;
                    $detailFillable['production_date'] = $finishingDhu->production_date;

                    TqmFinishingDhuDetails::query()->updateOrCreate([
                        'tqm_finishing_dhu_id' => $detailFillable['tqm_finishing_dhu_id'],
                        'tqm_defect_id' => $detailFillable['tqm_defect_id'],
                    ], $detailFillable);
                }
            }
            DB::commit();
            return response()->json('Saved Successfully.', Response::HTTP_OK);
        } catch (\Throwable $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
