<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api\Handler;


use SkylarkSoft\GoRMG\HR\Models\HrDepartment;

class DepartmentStore
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function store()
    {
        try {
            $id = $this->request->id ?? '';
            $department = HrDepartment::findOrNew($id);
            $department->fill($this->request->all());
            $department->save();

            return $department;
        } catch (\Exception $exception) {
            return false;
        }
    }

    public function show($id)
    {

    }

    public function update()
    {

    }

    public function destroy($id)
    {

    }
}
