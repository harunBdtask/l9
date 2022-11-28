<?php

namespace SkylarkSoft\GoRMG\Knitting\Controllers\Program;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Knitting\Actions\KnittingProgram\KnitProgramMachineAttachDetachAction;
use SkylarkSoft\GoRMG\Knitting\Models\KnittingProgram;
use SkylarkSoft\GoRMG\Knitting\Models\KnittingProgramMachineDistribution;
use SkylarkSoft\GoRMG\Knitting\Requests\ProgramMachineFormRequest;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ProgramMachineController extends Controller
{
    /**
     * @param ProgramMachineFormRequest $request
     * @param $knittingProgram
     * @param KnitProgramMachineAttachDetachAction $machineAttachDetachAction
     * @return JsonResponse
     * @throws Throwable
     */

    public function store(ProgramMachineFormRequest            $request, $knittingProgram,
                          KnitProgramMachineAttachDetachAction $machineAttachDetachAction): JsonResponse
    {
        try {
            DB::beginTransaction();
            $knitProgramMachine = KnittingProgramMachineDistribution::query()->updateOrCreate(
                [
                    'plan_info_id' => $request->input('plan_info_id'),
                    'knitting_program_id' => $knittingProgram,
                    'machine_id' => $request->input('machine_id')
                ],
                $request->all()
            );
            $machineAttachDetachAction->attach(
                $knittingProgram,
                $request->get('machine_no'),
            );
            DB::commit();
            return response()->json($knitProgramMachine, Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param KnittingProgram $knittingProgram
     * @return JsonResponse
     */
    public function show(KnittingProgram $knittingProgram): JsonResponse
    {
        try {
            $knittingProgram->load('machines.machine');
            return response()->json($knittingProgram, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param KnittingProgramMachineDistribution $knittingProgramMachine
     * @param KnitProgramMachineAttachDetachAction $machineAttachDetachAction
     * @return JsonResponse
     * @throws Throwable
     */

    public function destroy(KnittingProgramMachineDistribution   $knittingProgramMachine,
                            KnitProgramMachineAttachDetachAction $machineAttachDetachAction): JsonResponse
    {
        try {
            DB::beginTransaction();
            $knitProgramId = $knittingProgramMachine->knitting_program_id;
            $machineNo = $knittingProgramMachine->machine->machine_no;
            $knittingProgramMachine->delete();
            $machineAttachDetachAction->detach($knitProgramId, $machineNo);
            DB::commit();
            return response()->json(null, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
