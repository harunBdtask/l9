<?php

namespace SkylarkSoft\GoRMG\HR\Repositories;


use SkylarkSoft\GoRMG\HR\Models\HrSection;
use SkylarkSoft\GoRMG\HR\Requests\SectionRequest;

class SectionRepository
{
    public function all($departmentId = null)
    {
        return HrSection::orderBy('name', 'asc')->when($departmentId, function ($q) use ($departmentId) {
            $q->where('department_id', $departmentId);
        })->get();
    }

    public function paginate($departmentId = null)
    {
        if ($departmentId) {
            return HrSection::orderBy('name', 'asc')->where('department_id', $departmentId)->paginate();
        }

        return HrSection::orderBy('name', 'asc')->paginate();
    }

    public function store(SectionRequest $request)
    {
        try {
            $id = $request->id ?? '';

            $department = HrSection::findOrNew($id);
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
            return HrSection::find($id);
        } catch (\Exception $exception) {
            return false;
        }
    }

    public function update(SectionRequest $request)
    {
        return $this->store($request);
    }

    public function destroy($id)
    {
        try {
            $department = HrSection::find($id);
            $department->delete();

            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }
}
