<?php

namespace SkylarkSoft\GoRMG\Inventory\Services;

use Illuminate\Support\Facades\Notification;
use SkylarkSoft\GoRMG\Approval\Models\Approval;
use SkylarkSoft\GoRMG\Inventory\Models\YarnReceive;
use SkylarkSoft\GoRMG\Inventory\Notifications\YarnReceiveApprovalNotification;
use SkylarkSoft\GoRMG\Merchandising\Services\Approval\ApprovalPermittedUserService;

class YarnReceiveUpdateNotificationService
{
    private $receive;

    private function __construct(YarnReceive $yarnReceive)
    {
        $this->receive = $yarnReceive;
        return $this;
    }

    public static function for(YarnReceive $yarnReceive): self
    {
        return new static($yarnReceive);
    }

    public function notify()
    {
        if ($this->receive) {
            $this->approvalNotify();
        }
    }

    private function isReadyToApproved(): bool
    {
        $changes = $this->receive->getChanges();
        return array_key_exists('ready_to_approve', $changes) && $changes['ready_to_approve'] == 1;
    }

    private function isUnapprovedRequest(): bool
    {
        $changes = $this->receive->getChanges();
        return array_key_exists('un_approve_request', $changes) &&
            $changes['un_approve_request'] &&
            $this->receive->is_approve == 1;
    }

    private function approvalNotify()
    {
        $notificationData = [
            'receive_no' => $this->receive->receive_no,
        ];

        $approvalPermittedUser = ApprovalPermittedUserService::for(Approval::YARN_STORE_APPROVAL)
            ->setStep($this->receive->step);

        if ($this->isUnapprovedRequest()) {
            $approvalType = 'unapprove';
            $notificationData['approval_type'] = $approvalType;
            $approvalPermittedUser->setApprovalType($approvalType);

            Notification::send($approvalPermittedUser->get(), new YarnReceiveApprovalNotification($notificationData));
        } else if ($this->isReadyToApproved()) {
            $approvalType = 'approve';
            $notificationData['approval_type'] = $approvalType;
            $approvalPermittedUser->setApprovalType($approvalType);

            Notification::send($approvalPermittedUser->get(), new YarnReceiveApprovalNotification($notificationData));
        }
    }
}
