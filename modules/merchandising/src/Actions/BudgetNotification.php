<?php

namespace SkylarkSoft\GoRMG\Merchandising\Actions;

use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Notification;
use SkylarkSoft\GoRMG\Approval\Models\Approval;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Merchandising\Models\PriceQuotation;
use SkylarkSoft\GoRMG\Merchandising\Notifications\BudgetReadyForApprovalNotification;
use SkylarkSoft\GoRMG\Merchandising\Notifications\BudgetUnApprovalRequestNotification;
use SkylarkSoft\GoRMG\Merchandising\Services\Approval\ApprovalPermittedUserService;

class BudgetNotification
{
    public static function send($budget)
    {
        $changes = $budget->getChanges();
        $isChangedForReadyToApproved = array_key_exists('ready_to_approved', $changes) && $changes['ready_to_approved'] == 'Yes';
        $isChangedForUnapprovedRequest = array_key_exists('un_approve_request', $changes) &&
            $changes['un_approve_request'] != '' &&
            $budget->is_approve == Budget::APPROVED;

        $notificationData = [
            'job_no' => $budget->job_no,
            'buyer_id' => $budget->buyer_id,
            'factory_id' => $budget->factory_id,
        ];

        if ($isChangedForUnapprovedRequest) {
            $approvalPermittedUser = ApprovalPermittedUserService::for(Approval::BUDGET_APPROVAL)
                ->setApprovalType('unapprove')
                ->setBuyer($budget->buyer_id)
                ->setStep($budget->step)
                ->get();
            Notification::send($approvalPermittedUser, new BudgetUnApprovalRequestNotification($notificationData));
        }

        if ($isChangedForReadyToApproved) {
            $approvalPermittedUser = ApprovalPermittedUserService::for(Approval::BUDGET_APPROVAL)
                ->setApprovalType('approve')
                ->setBuyer($budget->buyer_id)
                ->setStep($budget->step)
                ->get();
            Notification::send($approvalPermittedUser, new BudgetReadyForApprovalNotification($notificationData));
        }
    }
}
