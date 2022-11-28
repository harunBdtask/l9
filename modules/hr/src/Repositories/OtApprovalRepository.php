<?php

namespace SkylarkSoft\GoRMG\HR\Repositories;

use SkylarkSoft\GoRMG\HR\Models\HrOtApproval;
use SkylarkSoft\GoRMG\HR\Models\HrOtApprovalDetail;

class OtApprovalRepository
{
    public function get()
    {
        return HrOtApproval::orderBy('ot_date', 'desc')->get();
    }

    public function paginate()
    {
        return HrOtApproval::orderBy('ot_date', 'desc')->paginate();
    }

    public function store($request)
    {
        $ot_approval = new HrOtApproval;
        $ot_approval->ot_date = $request->ot_date;
        $ot_approval->ot_start_time = $request->ot_start_time;
        $ot_approval->ot_end_time = $request->ot_end_time;
        $ot_approval->ot_for = $request->ot_for;
        $ot_approval->approved_by = $request->approved_by;
        $ot_approval->remarks = $request->remarks;

        if ($request->hasFile('file')) {
            $prev_file_path = storage_path() . "/app/public/ot_approval_files/" . $ot_approval->file;
            if (file_exists($prev_file_path) && !empty($ot_approval->file)) {
                unlink($prev_file_path);
            }
            $time = time();
            $file = $request->file;
            $file->storeAs('public/ot_approval_files', $time . $file->getClientOriginalName());
            $ot_approval->file = $time . $file->getClientOriginalName();
        }

        $ot_approval->save();

        $ot_approval_id = $ot_approval->id;
        $this->storeMultiple($request, $ot_approval_id);
        return $ot_approval;
    }

    private function storeMultiple($request, $ot_approval_id)
    {
        $section_ids = $request->section_id;
        foreach ($section_ids as $key => $section_id) {
            $ot_approval_details = new HrOtApprovalDetail();
            $ot_approval_details->ot_approval_id = $ot_approval_id;
            $ot_approval_details->ot_date = $request->ot_date;
            $ot_approval_details->ot_start_time = $request->ot_start_time;
            $ot_approval_details->ot_end_time = $request->ot_end_time;
            $ot_approval_details->ot_for = $request->ot_for;
            $ot_approval_details->department_id = $request->department_id[$key];
            $ot_approval_details->section_id = $section_id;
            $ot_approval_details->approved_by = $request->approved_by;
            $ot_approval_details->remarks = $request->remarks;
            $ot_approval_details->save();
        }
        return true;
    }
}
