<?php

namespace SkylarkSoft\GoRMG\HR\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeEducationInfoResource extends JsonResource
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
            'degree' => $this->degree,
            'institution' => $this->institution,
            'board' => $this->board,
            'result' => $this->result,
            'year' => $this->year,
        ];
    }
}
