<?php

namespace SkylarkSoft\GoRMG\HR\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LeaveApplicationResource extends JsonResource
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
            "id"               => $this->id,
            "department_id"    => $this->department_id,
            "department"       => $this->department,
            "designation_id"   => $this->designation_id,
            "designation"      => $this->designation,
            "section_id"       => $this->section_id,
            "section"          => $this->section,
            "employee_id"      => $this->employee_id,
            "employee"         => $this->employee,
            "applicant_name"   => $this->applicant_name,
            "reason"           => $this->reason,
            "duration"         => $this->duration,
            "application_date" => $this->application_date,
            "leave_date"       => $this->leave_date,
            "rejoin_date"      => $this->rejoin_date,
            "contact_details"  => $this->contact_details,
            "application_for"  => $this->application_for,
            "is_approved"      => $this->is_approved,
            "code"             => $this->code,
            "details"          => $this->details
        ];
    }
}
