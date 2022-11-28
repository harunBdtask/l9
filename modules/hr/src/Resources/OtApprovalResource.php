<?php

namespace SkylarkSoft\GoRMG\HR\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use SkylarkSoft\GoRMG\HR\Models\HrOtApproval;

class OtApprovalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $start_time_format = Carbon::parse($this->ot_date.' '.$this->ot_start_time)->format("h:i:s a");
        $end_time_format = Carbon::parse($this->ot_date.' '.$this->ot_end_time)->format("h:i:s a");
        return [
            'id' => $this->id,
            'ot_date' => $this->ot_date,
            'ot_date_formatted' => isset($this->ot_date) ? date('d/m/Y', strtotime($this->ot_date)) : '',
            'ot_start_time' => $this->ot_start_time,
            'ot_end_time' => $this->ot_end_time,
            'ot_start_time_format' => $start_time_format,
            'ot_end_time_format' => $end_time_format,
            'ot_for' => $this->ot_for,
            'ot_for_view' => isset($this->ot_for) ? HrOtApproval::OT_FOR[$this->ot_for] : '',
            'file' => $this->file,
            'file_link' => ($this->file && Storage::exists('public/ot_approval_files/'.$this->file)) ? asset('/')."storage/ot_approval_files/".$this->file : '#',
            'approved_by' => $this->approved_by,
            'remarks' => $this->remarks,
            'ot_approval_details' => $this->otApprovalDetails,
            'departments' => implode(', ', $this->otApprovalDetails->pluck('department.name')->toArray()),
            'sections' => implode(', ', $this->otApprovalDetails->pluck('section.name')->toArray()),
            'department_ids' => $this->otApprovalDetails->pluck('department_id')->toArray(),
            'section_ids' => $this->otApprovalDetails->pluck('section_id')->toArray()
        ];
    }
}
