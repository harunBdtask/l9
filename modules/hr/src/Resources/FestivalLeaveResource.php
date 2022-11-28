<?php

namespace SkylarkSoft\GoRMG\HR\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FestivalLeaveResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'leave_date' => $this->leave_date,
            'leave_date_formatted' => $this->leave_date ? date('d/m/Y', strtotime($this->leave_date)) : '',
        ];
    }
}
