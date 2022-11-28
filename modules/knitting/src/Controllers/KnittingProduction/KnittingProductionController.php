<?php

namespace SkylarkSoft\GoRMG\Knitting\Controllers\KnittingProduction;

use App\Exceptions\DeleteNotPossibleException;
use App\Http\Controllers\Controller;
use DNS1D;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Knitting\Actions\KnittingProduction\CollarCuffDetailsAction;
use SkylarkSoft\GoRMG\Knitting\Actions\KnittingProduction\KnittingRollProductionQtyAction;
use SkylarkSoft\GoRMG\Knitting\Actions\KnittingQC\KnitCardRollQCAction;
use SkylarkSoft\GoRMG\Knitting\Models\KnitProgramRoll;
use SkylarkSoft\GoRMG\Knitting\Models\KnittingProgramCollarCuffProduction;
use SkylarkSoft\GoRMG\Knitting\Requests\KnittingProductionRollFormRequest;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class KnittingProductionController extends Controller
{
    public function index()
    {
        return view('knitting::knittingProduction.index');
    }

    /**
     * @param $knitCardId
     * @return JsonResponse
     */
    public function show($knitCardId): JsonResponse
    {
        try {
            $knitProgramRolls = KnitProgramRoll::query()
                ->with(['knittingProgramCollarCuffProductions', 'shift', 'operator'])
                ->where('knit_card_id', $knitCardId)
                ->get()->map(function ($knitProgramRoll) {
                    $knitProgramRoll['barcode_view'] = DNS1D::getBarcodeSVG(($knitProgramRoll->barcode_no), "C128A", 1, 16, '', false);
                    return $knitProgramRoll;
                });

            return response()->json($knitProgramRolls, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    /**
     * @throws Throwable
     */
    public function store(KnittingProductionRollFormRequest $request, CollarCuffDetailsAction $collarCuffDetailsAction): JsonResponse
    {
        try {
            DB::beginTransaction();
            $knitRoll = KnitProgramRoll::query()->firstOrNew(['id' => $request->input('id')]);
            $knitRoll->fill($request->all())->save();
            $collarCuffDetails = $request->input('collar_cuff_details');
            $collarCuffDetailsAction->attach($collarCuffDetails, $knitRoll);

            KnittingRollProductionQtyAction::handle($knitRoll->plan_info_id);
            KnitCardRollQCAction::handle($request->get('knit_card_id'));

            DB::commit();
            return response()->json($knitRoll, Response::HTTP_CREATED);
        } catch (Exception $exception) {
            DB::rollBack();
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @throws Throwable
     */
    public function delete(KnitProgramRoll $knitProgramRoll, CollarCuffDetailsAction $collarCuffDetailsAction): JsonResponse
    {
        try {
            DB::beginTransaction();
            $planInfoId = $knitProgramRoll->plan_info_id;
            throw_if($knitProgramRoll->qc_roll_weight != null, new DeleteNotPossibleException('Qc weight Exists for this Production!'));
            $collarCuffDetailsAction->deleteKnittingProgramCollarCuffProduction($knitProgramRoll);

            $knitProgramRoll->delete();
            KnittingRollProductionQtyAction::handle($planInfoId);
            DB::commit();
            return response()->json($knitProgramRoll, Response::HTTP_OK);
        } catch (Exception $exception) {
            DB::rollBack();
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
