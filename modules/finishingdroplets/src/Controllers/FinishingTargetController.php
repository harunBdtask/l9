<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Finishingdroplets\Models\FinishingTarget;
use SkylarkSoft\GoRMG\Finishingdroplets\Requests\FinishingTargetRequest;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsProductionEntry;
use Symfony\Component\HttpFoundation\Response;

class FinishingTargetController extends Controller
{
    public function index()
    {
        return view('finishingdroplets::pages.finishing_targets');
    }

    /**
     * @param Request $request
     * @return false|JsonResponse
     */
    public function getList(Request $request)
    {
        $productionDate = $request->get('production_date') ?? date("Y-m-d");
        $floorId = $request->get('finishing_floor_id') ?? null;
        $tableId = $request->get('finishing_table_id') ?? null;

        $entryOption = GarmentsProductionEntry::query()
            ->where('factory_id', factoryId())
            ->select('finishing_target_entry_option')
            ->first()
            ->finishing_target_entry_option;

        if ($entryOption == 2 && !$tableId) {
            return false;
        }

        $targets = FinishingTarget::query()
            ->with([
                'buyer:name,id',
                'order:style_name,id',
                'item:name,id'
            ])->where([
                'production_date' => $productionDate,
                'finishing_floor_id' => $floorId,
                'factory_id' => factoryId(),
            ])
            ->when($tableId, function ($query, $tableId) {
                return $query->where('finishing_table_id', $tableId);
            })
            ->orderByDesc('id')
            ->get();
        if ($entryOption == 1) {
            $targets = collect($targets)->whereNull('finishing_table_id')->values();
        }

        return response()->json($targets);
    }

    /**
     * @param FinishingTargetRequest $request
     * @return JsonResponse
     */
    public function store(FinishingTargetRequest $request): JsonResponse
    {
        try {
            FinishingTarget::query()
                ->updateOrCreate(
                    ['id' => $request->get('id') ?? null],
                    $request->all()
                )
                ->save();
            return response()->json("Successfully Stored", Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @return JsonResponse
     */
    public function fetchGarmentsProductionEntryOption(): JsonResponse
    {
        $entry_option = GarmentsProductionEntry::query()
                ->where('factory_id', factoryId())
                ->select('finishing_target_entry_option')
                ->first()->finishing_target_entry_option ?? 1;
        return response()->json($entry_option);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
            FinishingTarget::query()->findOrFail($id)->delete();
            return response()->json("Successfully Deleted", Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
