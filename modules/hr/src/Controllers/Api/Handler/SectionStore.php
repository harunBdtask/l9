<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api\Handler;

use SkylarkSoft\GoRMG\HR\Models\HrSection;

class SectionStore
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
            $section = HrSection::findOrNew($id);
            $section->fill($this->request->all());
            $section->save();
            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }


}
