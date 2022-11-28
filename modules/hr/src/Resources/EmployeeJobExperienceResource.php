<?php

namespace SkylarkSoft\GoRMG\HR\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeJobExperienceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'employee_id' => $this->employee_id,
            'company_name' => $this->company_name,
            'ex_job_designation' => $this->ex_job_designation,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
            'ex_job_salary' => $this->ex_job_salary,
            'leave_reason' => $this->leave_reason
        ];
    }
}
