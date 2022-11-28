<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services;

use Carbon\Carbon;
use SkylarkSoft\GoRMG\Approval\Models\Approval;
use SkylarkSoft\GoRMG\Approval\Models\ApprovalDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\ReportSignature;
use SkylarkSoft\GoRMG\SystemSettings\PageReportNameConst;

class ReportSignatureService
{
    public static function getSignatures($page, $buyer = null)
    {
        return ReportSignature::query()
            ->isNotTemplate()
            ->with('details')
            ->when($buyer, function ($q, $buyer) {
                $q->whereJsonContains('buyer_id', $buyer);
            })
            ->where('page_name', PageReportNameConst::PAGES[$page])
            ->first() ?? [];
    }

    public static function getApprovalSignature($instance, $id, $approvalType = null): array
    {
        $data['signature_type'] = $approvalType ?? 'approval';
        $data['details'] = ApprovalDetail::query()
            ->with('user')
            ->where('approval_detailable_type', $instance)
            ->where('approval_detailable_id', $id)
            ->where('type', Approval::APPROVED)
            ->orderBy('id', 'asc')
            ->groupBy('user_id')
            ->get()
            ->map(function ($detail) use (&$data) {
                return [
                    'signature' => $detail->user->signature ?? null,
                    'designation' => $detail->user->designation ?? null,
                    'full_name' => $detail->user->full_name ?? null,
                    'department' => $detail->user->departmnt->department_name ?? null,
                    'date_time' => Carbon::make($detail->created_at)->toDateTimeString(),
                ];
            });

        $instanceData = $instance::query()->with('createdBy.departmnt')->where('id', $id)->first();
        $data['created_by'] = $instanceData->createdBy;
        $data['created_at'] = $instanceData->created_at;

        return $data;
    }

    public static function getGatePassSignature($instance, $id)
    {
        // TODO
    }
}
