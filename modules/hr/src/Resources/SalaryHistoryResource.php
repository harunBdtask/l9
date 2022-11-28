<?php

namespace SkylarkSoft\GoRMG\HR\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class SalaryHistoryResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'employee_id' => $this->employee_id,
            'employee_details' => $this->employee,
            'department_id' => $this->department_id,
            'department_details' => $this->department,
            'designation_id' => $this->designation_id,
            'designation_details' => $this->designation,
            'year' => $this->year,
            'gross_salary' => $this->gross_salary,
        ];
    }
}
