<?php

namespace SkylarkSoft\GoRMG\HR\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class EmployeeDropdownResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * @param $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'screen_name' => $this->screen_name . ' ( ' . $this->unique_id  . ' ) ',
            'unique_id' => $this->unique_id
        ];
    }
}
