<?php

namespace SkylarkSoft\GoRMG\Knitting\Controllers\Program;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Knitting\Actions\KnittingProgram\KnittingProgramQtyAction;
use SkylarkSoft\GoRMG\Knitting\Models\KnittingProgram;
use SkylarkSoft\GoRMG\Knitting\Models\KnittingProgramColorsQty;
use SkylarkSoft\GoRMG\Knitting\Requests\KnitProgramFabricColorRequest;
use SkylarkSoft\GoRMG\Knitting\Services\KnitProgramFabricColorService;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ProgramFabricColorController extends Controller
{
    public function getData($knit_program_id, $plan_info_id): JsonResponse
    {
        try {
            $formData = (new KnitProgramFabricColorService($knit_program_id, $plan_info_id))->formatData();
            $responseStatus = Response::HTTP_OK;
            $message = "Success!";
        } catch (Exception $e) {
            dd($e);
            $responseStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
            $error = $e->getMessage();
            $message = "Something went wrong!";
        }

        return response()->json([
            'data' => $formData ?? [],
            'status' => $responseStatus,
            'message' => $message,
            'error' => $error ?? null,
        ]);
    }

    /**
     * @throws Throwable
     */
    public function saveData($knit_program_id, $plan_info_id, KnitProgramFabricColorRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $item_colors = $request->item_color;
            $message = "Successfully Stored!";
            foreach ($item_colors as $key => $item_color) {
                if ($request->id[$key]) {
                    $message = "Successfully Updated!";
                }
                $programColor = KnittingProgramColorsQty::findOrNew($request->id[$key]);
                $programColor->plan_info_id = $plan_info_id;
                $programColor->knitting_program_id = $knit_program_id;
                $programColor->item_color_id = $request->item_color_id[$key];
                $programColor->item_color = $request->item_color[$key];
                $programColor->booking_qty = $request->booking_qty[$key];
                $programColor->program_qty = $request->program_qty[$key];
                $programColor->save();
            }
            $data = KnittingProgramColorsQty::query()
                ->where([
                    'plan_info_id' => $plan_info_id,
                    'knitting_program_id' => $knit_program_id,
                ])
                ->get();
            $this->updateProgramQty($knit_program_id);
            KnittingProgramQtyAction::handle($plan_info_id);
            DB::commit();
            $responseStatus = Response::HTTP_OK;
        } catch (Exception $e) {
            $responseStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
            $error = $e->getMessage();
            $message = "Something went wrong!";
        }
        return response()->json([
            'data' => $data ?? [],
            'status' => $responseStatus,
            'message' => $message,
            'error' => $error ?? null,
        ]);
    }

    private function updateProgramQty($knit_program_id)
    {
        $programQty = KnittingProgramColorsQty::query()
                ->where('knitting_program_id', $knit_program_id)
                ->sum('program_qty');
        KnittingProgram::query()->where('id', $knit_program_id)->update([
            'program_qty' => $programQty
        ]);
    }
}
