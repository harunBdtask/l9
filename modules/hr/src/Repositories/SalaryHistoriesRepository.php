<?php


namespace SkylarkSoft\GoRMG\HR\Repositories;

use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\HR\Models\HrSalaryHistory;
use SkylarkSoft\GoRMG\HR\Requests\SalaryHistoriesRequest;

class SalaryHistoriesRepository
{
    public function all()
    {
        return HrSalaryHistory::all();
    }

    public function paginate()
    {
        return HrSalaryHistory::paginate();
    }

    public function store(SalaryHistoriesRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = collect($request["salary_histories"])->map(function ($item) {
                return [
                    'employee_id'       => $item['employee_id'],
                    'designation_id'    => $item['designation_id'],
                    'department_id'     => $item['department_id'],
                    'year'              => $item['year'],
                    'gross_salary'      => $item['gross_salary'],
                    'factory_id'       => factoryId(),
                ];
            })->all();
            HrSalaryHistory::insert($data);
            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            return false;
        }
    }

    public function show($id)
    {
        try {
            return HrSalaryHistory::where('employee_id', $id)->get();
        } catch (\Exception $exception) {
            return false;
        }
    }

    public function showById($id)
    {
        try {
            return HrSalaryHistory::find($id);
        } catch (\Exception $exception) {
            return false;
        }
    }

    public function update($id, SalaryHistoriesRequest $request)
    {
//        return var_dump($request["salary_histories"][0]);
        try {
            $process = HrSalaryHistory::find($id);
            $process->update($request["salary_histories"][0]);
            return $process;
        } catch (\Exception $e) {
            return false;
        }
//        try {
//            DB::beginTransaction();
//            SalaryHistory::where('id', $id)
//                ->update($request["salary_histories"][0]);
//            DB::commit();
//            return true;
//        } catch (\Exception $exception) {
//            DB::rollBack();
//            return false;
//        }
    }

    public function destroy($id)
    {
        try {
            $grade = HrSalaryHistory::find($id);
            $grade->delete();
            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }
}
