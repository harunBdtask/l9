<?php

namespace SkylarkSoft\GoRMG\HR\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeDocumentInfoResource extends JsonResource
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
            'id'                    => $this->id,
            'employee_id'           => $this->employee_id,
            'nid'                   => $this->nid,
            'birth_certificate'     => $this->birth_certificate,
            'photo'                 => $this->photo,
            'character_certificate' => $this->character_certificate,
            'ssc_certificate'       => $this->ssc_certificate,
            'hsc_certificate'       => $this->hsc_certificate,
            'biodata'               => $this->biodata,
            'medical_certificate'   => $this->medical_certificate,
            'signature'             => $this->signature,
            'masters'               => $this->masters,
            'hons'                  => $this->hons,
            'others'                => $this->others
        ];
    }
}
