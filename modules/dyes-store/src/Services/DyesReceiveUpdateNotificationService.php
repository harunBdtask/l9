<?php

namespace SkylarkSoft\GoRMG\DyesStore\Services;

use Illuminate\Support\Facades\Notification;
use SkylarkSoft\GoRMG\Approval\Models\Approval;
use SkylarkSoft\GoRMG\DyesStore\Models\DyesChemicalsReceive;
use SkylarkSoft\GoRMG\DyesStore\Notifications\DyesReceiveApprovalNotification;
use SkylarkSoft\GoRMG\Merchandising\Services\Approval\ApprovalPermittedUserService;

class DyesReceiveUpdateNotificationService
{
    private $dyesChemicalsReceive;

    private function __construct(DyesChemicalsReceive $dyesChemicalsReceive)
    {
        $this->dyesChemicalsReceive = $dyesChemicalsReceive;
        return $this;
    }

    public static function for(DyesChemicalsReceive $dyesChemicalsReceive): self
    {
        return new static($dyesChemicalsReceive);
    }

    public function notify()
    {
        if ($this->dyesChemicalsReceive) {
            $this->approvalNotify();
        }
    }

    private function isReadyToApproved(): bool
    {
        $changes = $this->dyesChemicalsReceive->getChanges();
        return array_key_exists('ready_to_approve', $changes) && $changes['ready_to_approve'] == 1;
    }

    private function isUnapprovedRequest(): bool
    {
        $changes = $this->dyesChemicalsReceive->getChanges();
        return array_key_exists('un_approve_request', $changes) &&
            $changes['un_approve_request'] &&
            $this->dyesChemicalsReceive->is_approve == 1;
    }

    private function approvalNotify()
    {
        $notificationData = [
            'receive_no' => $this->dyesChemicalsReceive->system_generate_id,
        ];

        $approvalPermittedUser = ApprovalPermittedUserService::for(Approval::DYES_CHEMICAL_STORE_APPROVAL)
            ->setStep($this->dyesChemicalsReceive->step);

        if ($this->isUnapprovedRequest()) {
            $approvalType = 'unapprove';
            $notificationData['approval_type'] = $approvalType;
            $approvalPermittedUser->setApprovalType($approvalType);

            Notification::send($approvalPermittedUser->get(), new DyesReceiveApprovalNotification($notificationData));
        } else if ($this->isReadyToApproved()) {
            $approvalType = 'approve';
            $notificationData['approval_type'] = $approvalType;
            $approvalPermittedUser->setApprovalType($approvalType);

            Notification::send($approvalPermittedUser->get(), new DyesReceiveApprovalNotification($notificationData));
        }
    }
}
