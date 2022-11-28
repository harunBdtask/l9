<?php

namespace SkylarkSoft\GoRMG\HR\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'userid' => $this->userid,
            'date' => $this->date ? date('d/m/Y', strtotime($this->date)) : '',
            'raw_date' => $this->date,
            'name' => $this->name,
            'att_in' => $this->att_in,
            'att_break' => $this->att_break,
            'att_resume' => $this->att_resume,
            'att_out' => $this->att_out,
            'att_ot' => $this->att_ot,
            'att_done' => $this->att_done,
            'workhour' => $this->workhour,
            'othour' => $this->othour,
            'screen_name' => $this->employeeOfficialInfo->employeeBasicInfo->screen_name,
            'department' => $this->employeeOfficialInfo->departmentDetails->name,
            'designation' => $this->employeeOfficialInfo->designationDetails->name,
            'section' => $this->employeeOfficialInfo->sectionDetails->name,
            'type' => $this->employeeOfficialInfo->type,
            'code' => $this->employeeOfficialInfo->code,
        ];
    }
}
