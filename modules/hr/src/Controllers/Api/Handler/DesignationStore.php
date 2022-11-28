<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api\Handler;



use SkylarkSoft\GoRMG\HR\Models\HrDesignation;

class DesignationStore
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
            $designation = HrDesignation::findOrNew($id);
            $designation->fill($this->request->all());
            $designation->save();
            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }


}
