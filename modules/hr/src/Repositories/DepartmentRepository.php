<?php

namespace SkylarkSoft\GoRMG\HR\Repositories;


use SkylarkSoft\GoRMG\HR\Models\HrDepartment;
use SkylarkSoft\GoRMG\HR\Requests\DepartmentRequest;

class DepartmentRepository
{
    public function all()
    {
        return HrDepartment::with('sections')->orderBy('name', 'asc')->get();
    }

    public function paginate()
    {
        return HrDepartment::orderBy('name', 'asc')->paginate();
    }

    public function store(DepartmentRequest $request)
    {
        try {
            $id = $request->id ?? '';
            $department = HrDepartment::findOrNew($id);
            $department->fill($request->all());
            $department->save();
            return $department;
        } catch (\Exception $exception) {
            return false;
        }
    }

    public function show($id)
    {
        try {
            return HrDepartment::find($id);
        } catch (\Exception $exception) {
            return false;
        }
    }

    public function update(DepartmentRequest $request)
    {
        return $this->store($request);
    }

    public function destroy($id)
    {
        try {
            $department = HrDepartment::find($id);
            $department->delete();
            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }
}
