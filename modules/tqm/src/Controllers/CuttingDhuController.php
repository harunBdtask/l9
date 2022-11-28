<?php

namespace SkylarkSoft\GoRMG\TQM\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\TQM\Models\TqmCuttingDhu;
use SkylarkSoft\GoRMG\TQM\Models\TqmCuttingDhuDetails;
use SkylarkSoft\GoRMG\TQM\Requests\CuttingDhuRequest;
use SkylarkSoft\GoRMG\TQM\Services\CuttingDhuDataService;
use Symfony\Component\HttpFoundation\Response;

class CuttingDhuController extends Controller
{
    public function bundleCardsData(Request $request): JsonResponse
    {
        try {
            $bundleCards = (new CuttingDhuDataService($request))->getData();
            return response()->json($bundleCards, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(CuttingDhuRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            foreach ($request->all() as $data) {
                $criteria = CuttingDhuDataService::setTqmCuttingCriteria($data);
                $criteria['production_date'] = $data['production_date'];

                $cuttingDhu = TqmCuttingDhu::query()->updateOrCreate($criteria, $data);

                foreach ($data['details'] as $detail) {
                    $detailFillable = array_merge($detail, CuttingDhuDataService::setTqmCuttingCriteria($cuttingDhu));
                    $detailFillable['tqm_cutting_dhu_id'] = $cuttingDhu->id;
                    $detailFillable['production_date'] = $cuttingDhu->production_date;

                    TqmCuttingDhuDetails::query()->updateOrCreate([
                        'tqm_cutting_dhu_id' => $detailFillable['tqm_cutting_dhu_id'],
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
