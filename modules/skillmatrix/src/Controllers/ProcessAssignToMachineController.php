<?php

namespace SkylarkSoft\GoRMG\Skillmatrix\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Skillmatrix\Models\ProcessAssignToMachine;
use SkylarkSoft\GoRMG\Skillmatrix\Models\SewingMachine;
use SkylarkSoft\GoRMG\Skillmatrix\Models\SewingProcess;
use SkylarkSoft\GoRMG\Skillmatrix\PackageConst;
use SkylarkSoft\GoRMG\Skillmatrix\Requests\ProcessAssignToMachineRequest;
use Symfony\Component\HttpFoundation\Response;

class ProcessAssignToMachineController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', null);
        $paginateNumber = intval($request->get('paginateNumber', 15));
        $processAssignToMachines = ProcessAssignToMachine::query()
            ->with([
                'sewingProcess:id,name',
                'sewingMachine:id,name',
            ])
            ->when($search, function ($query) use ($search) {
                return $query->whereHas('sewingProcess', function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%");
                })->orWhereHas('sewingMachine', function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%");
                });
            })
            ->orderBy('id', 'DESC')
            ->paginate($paginateNumber);

        return view(PackageConst::PACKAGE_NAME . '::pages.process_assign_to_machines',
            [
                'processAssignToMachines' => $processAssignToMachines,
                'paginateNumber' => $paginateNumber,
            ]);
    }

    public function create()
    {
        $sewingMachines = SewingMachine::sewingMachines();
        $sewingProcesses = SewingProcess::processes();

        return view(PackageConst::PACKAGE_NAME . '::forms.process_assign_to_machine',
            [
                'sewingProcesses' => $sewingProcesses,
                'sewingMachines' => $sewingMachines,
            ]);
    }

    public function store(ProcessAssignToMachineRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $inputs = $this->generateProcessAssignToMachineData(
                $request->get('sewing_process_id'),
                $request->get('sewing_machine_id')
            );

            $exception = DB::transaction(function () use ($inputs) {
                ProcessAssignToMachine::insert($inputs);
            });

            $status = Response::HTTP_OK;
            $message = 'Successfully added';

            if (!is_null($exception)) {
                $status = Response::HTTP_INTERNAL_SERVER_ERROR; // For transaction error
                $message = 'Not Successfully added';
            }
        } catch (Exception $e) {
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
            $message = $e->getMessage();
        } finally {
            return response()->json([
                'status' => $status,
                'message' => $message,
            ], $status);
        }
    }

    private function generateProcessAssignToMachineData($processIds, $machineId): array
    {
        return collect($processIds)
            ->map(function ($processId) use ($machineId) {
                return [
                    'sewing_machine_id' => $machineId,
                    'sewing_process_id' => $processId,
                    'created_by' => userId(),
                    'factory_id' => factoryId(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();
    }

    public function destroy($id)
    {
        try {
            ProcessAssignToMachine::destroy($id);
            Session::flash('success', S_DELETE_MSG);
        } catch (Exception $e) {
            Session::flash('error', E_DELETE_MSG);
        } finally {
            return redirect('process-assign-to-machines');
        }
    }
}
