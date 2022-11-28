<?php namespace SkylarkSoft\GoRMG\Knitting\Controllers\PlanningInfo;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Knitting\Models\PlanningInfoDetail;
use Symfony\Component\HttpFoundation\Response;

class PlanningInfoDetailController extends Controller
{

    protected function items()
    {
        return [
            'id',
            'total_qty',
            'percentage',
            'yarn_color',
            'supplier_id',
            'yarn_count_id',
            'planning_info_id',
            'composition_type_id',
            'yarn_composition_id',
        ];
    }

    public function edit(Request $request, $planning_info_id): JsonResponse
    {
        $planningInfoDetailList = PlanningInfoDetail::query()
            ->where('planning_info_id', $planning_info_id)->get($this->items());
        return response()->json($planningInfoDetailList, Response::HTTP_OK);
    }

    /**
     * @throws \Throwable
     */
    public function store(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $ignoreIds=[];
            $planningId=null;
            $planningInfoDetailList = [];
            foreach ($request->all() as $item) {
                $planningInfoDetailData = PlanningInfoDetail::query()->updateOrCreate([
                    'id'               => $item['id'] ?? null,
                    'planning_info_id' => $item['planning_info_id'],
                ], $item);
                $planningId=$item['planning_info_id'];
                $ignoreIds[] = $planningInfoDetailData['id'];
                $planningInfoDetailList[] = $planningInfoDetailData;
            }
            PlanningInfoDetail::query()
                ->where('planning_info_id',$planningId)
                ->whereNotIn('id',$ignoreIds)
                ->delete();
            DB::commit();
            return response()->json($planningInfoDetailList, Response::HTTP_CREATED);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
