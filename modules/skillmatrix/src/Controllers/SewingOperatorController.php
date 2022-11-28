<?php

namespace SkylarkSoft\GoRMG\Skillmatrix\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use SkylarkSoft\GoRMG\Skillmatrix\Models\ProcessAssignToMachine;
use SkylarkSoft\GoRMG\Skillmatrix\Models\SewingMachine;
use SkylarkSoft\GoRMG\Skillmatrix\Models\SewingOperator;
use SkylarkSoft\GoRMG\Skillmatrix\Models\SewingOperatorSkill;
use SkylarkSoft\GoRMG\Skillmatrix\Models\SewingProcess;
use SkylarkSoft\GoRMG\Skillmatrix\PackageConst;
use SkylarkSoft\GoRMG\Skillmatrix\Requests\SewingOperatorRequest;
use SkylarkSoft\GoRMG\SystemSettings\Models\Floor;
use Symfony\Component\HttpFoundation\Response;

class SewingOperatorController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', null);
        $paginateNumber = intval($request->get('paginateNumber', 15));
        $sewingOperators = SewingOperator::with('sewingOperatorSkills')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    return $query->orWhere('name', 'like', "%$search%")
                        ->orWhere('title', 'like', "%$search%")
                        ->orWhere('operator_grade', 'like', "%$search%")
                        ->orWhere('present_salary', 'like', "%$search%")
                        ->orWhere('operator_id', 'like', "%$search%");
                });
            })
            ->orderBy('id', 'DESC')
            ->paginate($paginateNumber);

        return view(PackageConst::PACKAGE_NAME . '::pages.sewing_operators',
            [
                'sewingOperators' => $sewingOperators,
                'paginateNumber' => $paginateNumber,
            ]);
    }

    private function floors(): array
    {
        return Floor::pluck('floor_no', 'id')->prepend('Select a floor', '')->all();
    }

    public function create()
    {
        $floors = $this->floors();

        return view(PackageConst::PACKAGE_NAME . '::forms.sewing_operator',
            [
                'sewingOperator' => null,
                'floors' => $floors,
            ]);
    }

    public function store(SewingOperatorRequest $request)
    {
        try {
            if ($request->hasFile('image')) {
                $file = $request->image;
                $fileName = time() . '_' . uniqid() . '_sewing_operator.' . $file->getClientOriginalExtension();
                $file->storeAs('sewing_operators', $fileName);
                $profile_image = $fileName;
            }
            $requestData = $request->all();
            $requestData['image'] = $profile_image ?? null;
            SewingOperator::create($requestData);

            Session::flash('success', S_SAVE_MSG);
        } catch (Exception $e) {
            Session::flash('error', \SOMETHING_WENT_WRONG . " " . $e->getMessage());
        } finally {
            return redirect('/sewing-operators');
        }
    }

    public function operatorSkills(SewingOperator $sewingOperator)
    {
        $sewingMachines = SewingMachine::pluck('name', 'id')->prepend('Select a machine', '')->all();

        return view(PackageConst::PACKAGE_NAME . '::forms.add_sewing_operator_skills',
            [
                'sewingOperator' => $sewingOperator,
                'sewingMachines' => $sewingMachines,
            ]);
    }

    public function operatorSkillsPost(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $operatorSkillInput = [];
            $sewingOperatorId = $request->get('operator_id');
            $sewingMachineId = $request->get('sewing_machine_id');
            $sewingProcessId = $request->get('sewing_process_id');
            $capacities = $request->get('capacity');

            if ($sewingProcessId) {
                foreach ($sewingProcessId as $key => $processId) {
                    if ($capacities[$key]) {
                        $operatorSkill = [
                            'sewing_operator_id' => $sewingOperatorId,
                            'sewing_machine_id' => $sewingMachineId,
                            'sewing_process_id' => $processId,
                            'capacity' => $capacities[$key],
                            'created_by' => userId(),
                            'factory_id' => factoryId(),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                        $operatorSkillInput[] = $operatorSkill;
                    }
                }

                if (!empty($operatorSkillInput)) {
                    $exception = DB::transaction(function () use ($operatorSkillInput, $sewingOperatorId, $sewingMachineId) {
                        $this->deleteSewingOperatorSkill($sewingOperatorId, $sewingMachineId);
                        SewingOperatorSkill::insert($operatorSkillInput);
                    });

                    if (is_null($exception)) {
                        $status = Response::HTTP_OK;
                        $message = S_UPDATE_MSG;
                    }
                } else {
                    $status = Response::HTTP_FORBIDDEN;
                    $message = 'Please select at least one row correctly';
                }
            } elseif ($request->get('edit') > 0) {
                $this->deleteSewingOperatorSkill($sewingOperatorId, $sewingMachineId);
                $status = Response::HTTP_OK;
                $message = S_UPDATE_MSG;
            } else {
                $status = Response::HTTP_FORBIDDEN;
                $message = 'Please select at least one process';
            }
        } catch (Exception $e) {
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
            $message = \SOMETHING_WENT_WRONG . " " . $e->getMessage();
        }

        return response()->json([
            'status' => $status,
            'message' => $message,
        ]);
    }

    public function deleteSewingOperatorSkill($sewingOperatorId, $sewingMachineId)
    {
        SewingOperatorSkill::where([
            'sewing_operator_id' => $sewingOperatorId,
            'sewing_machine_id' => $sewingMachineId,
        ])->forceDelete();

        return true;
    }

    public function show($id)
    {
        $sewingOperator = SewingOperator::with('sewingOperatorSkills')
            ->where('id', $id)
            ->first();

        return view(PackageConst::PACKAGE_NAME . '::pages.view_sewing_operator',
            [
                'sewingOperator' => $sewingOperator,
            ]);
    }

    public function edit($id)
    {
        $sewingOperator = SewingOperator::findOrFail($id);
        $floors = $this->floors();

        return view(PackageConst::PACKAGE_NAME . '::forms.sewing_operator',
            [
                'sewingOperator' => $sewingOperator,
                'floors' => $floors,
            ]);
    }

    public function update($id, SewingOperatorRequest $request)
    {
        try {
            $sewing_operator = SewingOperator::findOrFail($id);

            $requestData = $request->all();
            if ($request->hasFile('image')) {
                if ($sewing_operator->image && Storage::disk('public')->exists('/sewing_operators/' . $sewing_operator->image)) {
                    Storage::delete('sewing_operators/' . $sewing_operator->image);
                }
                $file = $request->image;
                $fileName = time() . '_' . uniqid() . '_sewing_operator.' . $file->getClientOriginalExtension();
                $file->storeAs('sewing_operators', $fileName);
                $profile_image = $fileName;
                $requestData['image'] = $profile_image ?? null;
            }
            $sewing_operator->update($requestData);
            Session::flash('success', S_UPDATE_MSG);
        } catch (Exception $e) {
            Session::flash('error', \SOMETHING_WENT_WRONG . " " . $e->getMessage());
        } finally {
            return redirect('/sewing-operators');
        }
    }

    public function destroy($id)
    {
        try {
            SewingOperator::destroy($id);
            Session::flash('success', S_DELETE_MSG);
        } catch (Exception $e) {
            Session::flash('error', \SOMETHING_WENT_WRONG . " " . $e->getMessage());
        } finally {
            return redirect('/sewing-operators');
        }
    }

    public function operatorSkillInventory(Request $request)
    {
        $search = $request->get('q');
        $operatorSearchColumn = $request->get('operator_search_column');
        $floorId = $request->get('floor_id');
        $lineId = $request->get('line_id');
        $sewingMachineId = $request->get('sewing_machine_id');
        $sewingProcessId = $request->get('sewing_process_id');

        $sewingOperators = SewingOperatorSkill::query()
            ->whereHas('sewingOperator', function ($query) use ($operatorSearchColumn, $search, $floorId, $lineId) {
                $query->when($operatorSearchColumn && $search, function ($q) use ($operatorSearchColumn, $search) {
                    $q->where($operatorSearchColumn, 'like', "%$search%");
                })->when($floorId, function ($q) use ($floorId) {
                    $q->where('floor_id', $floorId);
                })->when($lineId, function ($q) use ($lineId) {
                    $q->where('line_id', $lineId);
                });
            })->when($sewingMachineId, function ($query) use ($sewingMachineId) {
                $query->where('sewing_machine_id', $sewingMachineId);
            })->when($sewingProcessId, function ($query) use ($sewingProcessId) {
                $query->where('sewing_process_id', $sewingProcessId);
            })->paginate();

        $sewingMachines = SewingMachine::pluck('name', 'id')->prepend('Select a machine', '')->all();
        $processes = SewingProcess::pluck('name', 'id')->prepend('Select a process', '')->all();
        $floors = $this->floors();
        $operatorSearchColumns = SewingOperator::OPERATOR_SEARCH_COLUMNS;

        return view(PackageConst::PACKAGE_NAME . '::pages.operator_skill_inventory', [
            'sewingOperators' => $sewingOperators ?? [],
            'sewingMachines' => $sewingMachines,
            'processes' => $processes,
            'floors' => $floors,
            'operatorSearchColumns' => $operatorSearchColumns,
            'q' => $search,
        ]);
    }

    public function getProcessesByMachineId($sewingMachineId, $sewingOperatorId)
    {
        $sewingProcesses = ProcessAssignToMachine::with('sewingProcess:id,name,standard_capacity')
            ->where('sewing_machine_id', $sewingMachineId)
            ->select('sewing_machine_id', 'sewing_process_id')
            ->get();

        $sewingOperatorsSkills = SewingOperatorSkill::query()
            ->where('sewing_operator_id', $sewingOperatorId)
            ->whereIn('sewing_machine_id', $sewingProcesses->pluck('sewing_machine_id'))
            ->whereIn('sewing_process_id', $sewingProcesses->pluck('sewing_process_id'))
            ->get();

        foreach ($sewingProcesses as $key => $processData) {
            $operatorSkill = $sewingOperatorsSkills
                ->where('sewing_machine_id', $processData->sewing_machine_id)
                ->where('sewing_process_id', $processData->sewing_process_id)
                ->first();

            $sewingProcesses[$key]->process_assigned = $operatorSkill ? 1 : 0;
            $sewingProcesses[$key]->capacity = $operatorSkill->capacity ?? '';

            $efficiency = 0;
            if ($operatorSkill) {
                $standard_capacity = $processData->sewingProcess->standard_capacity ?? 0;
                if ($standard_capacity > 0 && $operatorSkill->capacity > 0) {
                    $efficiency = round((($operatorSkill->capacity * 100) / $standard_capacity), 1);
                }
            }
            $sewingProcesses[$key]->efficiency = $efficiency;
        }

        return view(PackageConst::PACKAGE_NAME . '::forms.process_assign_to_operator_form', compact('sewingProcesses'))->render();
    }
}
