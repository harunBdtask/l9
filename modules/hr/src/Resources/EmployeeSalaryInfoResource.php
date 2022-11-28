<?php

namespace SkylarkSoft\GoRMG\HR\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeSalaryInfoResource extends JsonResource
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
            'id'            => $this->id,
            'employee_id'   => $this->employee_id,
            'gross'         => $this->gross,
            'basic'         => $this->basic,
            'house_rent'    => $this->house_rent,
            'transport'     => $this->transport,
            'medical'       => $this->medical,
            'food'          => $this->food,
            'out_of_city'   => $this->out_of_city,
            'mobile_allowence' => $this->mobile_allowence,
            'attendance_bonus' => $this->attendance_bonus,
        ];
    }
}
