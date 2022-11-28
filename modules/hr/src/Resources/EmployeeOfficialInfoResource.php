<?php

namespace SkylarkSoft\GoRMG\HR\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeOfficialInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'employee_id'        => $this->employee_id,
            'department_id'      => $this->department_id,
            'designation_id'     => $this->designation_id,
            'section_id'         => $this->section_id,
            'grade_id'           => $this->grade_id,
            'code'               => $this->code,
            'type'               => $this->type,
            'unique_id'          => $this->unique_id,
            'punch_card_id'      => $this->punch_card_id,
            'date_of_joining'    => $this->date_of_joining,
            'job_permanent_date' => $this->job_permanent_date,
            'bgmea_id'           => $this->bgmea_id,
            'date_of_joining_bn' => $this->date_of_joining_bn,
            'bank_id'            => $this->bank_id,
            'account_no'         => $this->account_no,
            'reporting_to'       => $this->reporting_to,
            'shift_enabled'      => $this->shift_enabled,
            'shift_id'           => $this->shift_id,
        ];
    }
}
