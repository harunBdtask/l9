<?php

namespace SkylarkSoft\GoRMG\Knitting\Controllers\Program\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Knitting\Filters\Filter;
use SkylarkSoft\GoRMG\SystemSettings\Models\Machine;
use Symfony\Component\HttpFoundation\Response;

class MachineSearchApiController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $dia = $request->get('dia');
            $machineType = $request->get('machine_type') == 'null' ? null : ($request->get('machine_type') ?? null);
            $knittingFloorId = $request->get('knitting_floor_id') == 'null' ? null : ($request->get('knitting_floor_id') ?? null);

            $machines = Machine::query()
                ->with(['knitProgramMachineDistribute', 'knittingFloor'])
                ->where('machine_type', Machine::KNITTING)
                ->when($dia, Filter::applyFilter('machine_dia', $dia))
                ->when($machineType, Filter::applyFilter('machine_type_info', $machineType))
                ->when($knittingFloorId, Filter::applyFilter('knitting_floor_id', $knittingFloorId))
                ->get()->map(function ($machine) {
                    return [
                        'id' => $machine->id,
                        'status' => $machine->status,
                        'machine_id' => $machine->id,
                        'text' => $machine->machine_no,
                        'machine_no' => $machine->machine_no,
                        'machine_name' => $machine->machine_name,
                        'machine_dia' => $machine->machine_dia,
                        'machine_gg' => $machine->machine_gg,
                        'machine_type_info' => $machine->machine_type_info,
                        'machine_capacity' => $machine->machine_capacity,
                        'knit_machine_distribution_id' => optional($machine->knitProgramMachineDistribute)->id,
                        'distribution_qty' => optional($machine->knitProgramMachineDistribute)->distribution_qty,
                        'knitting_floor' => $machine->knittingFloor
                    ];
                });

            return response()->json($machines, Response::HTTP_OK);

        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
