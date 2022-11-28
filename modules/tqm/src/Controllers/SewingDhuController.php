<?php

namespace SkylarkSoft\GoRMG\TQM\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\TQM\Models\TqmCuttingDhu;
use SkylarkSoft\GoRMG\TQM\Models\TqmCuttingDhuDetails;
use SkylarkSoft\GoRMG\TQM\Models\TqmSewingDhu;
use SkylarkSoft\GoRMG\TQM\Models\TqmSewingDhuDetails;
use SkylarkSoft\GoRMG\TQM\Requests\SewingDhuRequest;
use SkylarkSoft\GoRMG\TQM\Services\CuttingDhuDataService;
use SkylarkSoft\GoRMG\TQM\Services\SewingDhuDataService;
use Symfony\Component\HttpFoundation\Response;

class SewingDhuController extends Controller
{
    public function sewingProductionData(SewingDhuRequest $request): JsonResponse
    {
        try {
            $sewingProductions = (new SewingDhuDataService($request))->getData();
            return response()->json($sewingProductions, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(SewingDhuRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            foreach ($request->all() as $data) {
                $criteria = SewingDhuDataService::setTqmSewingCriteria($data);
                $criteria['production_date'] = $data['production_date'];

                $sewingDhu = TqmSewingDhu::query()->updateOrCreate($criteria, $data);

                foreach ($data['details'] as $detail) {
                    $detailFillable = array_merge($detail, SewingDhuDataService::setTqmSewingCriteria($sewingDhu));
                    $detailFillable['tqm_sewing_dhu_id'] = $sewingDhu->id;
                    $detailFillable['production_date'] = $sewingDhu->production_date;

                    TqmSewingDhuDetails::query()->updateOrCreate([
                        'tqm_sewing_dhu_id' => $detailFillable['tqm_sewing_dhu_id'],
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
