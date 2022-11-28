<?php

namespace SkylarkSoft\GoRMG\HR\Repositories;


use SkylarkSoft\GoRMG\HR\Models\HrDesignation;
use SkylarkSoft\GoRMG\HR\Requests\DesignationRequest;

class DesignationRepository
{
    public function all()
    {
        return HrDesignation::orderBy('name', 'asc')->get();
    }

    public function paginate()
    {
        return HrDesignation::orderBy('name', 'asc')->paginate();
    }

    public function store(DesignationRequest $request)
    {
        try {
            $id = $request->id ?? '';

            $department = HrDesignation::findOrNew($id);
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
            return HrDesignation::find($id);
        } catch (\Exception $exception) {
            return false;
        }
    }

    public function update(DesignationRequest $request)
    {
        return $this->store($request);
    }

    public function destroy($id)
    {
        try {
            $department = HrDesignation::find($id);
            $department->delete();

            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }
}
