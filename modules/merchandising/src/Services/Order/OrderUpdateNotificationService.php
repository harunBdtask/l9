<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\Order;

use Illuminate\Support\Facades\Notification;
use SkylarkSoft\GoRMG\Approval\Models\Approval;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Notifications\OrderApprovalNotification;
use SkylarkSoft\GoRMG\Merchandising\Services\Approval\ApprovalPermittedUserService;

class OrderUpdateNotificationService
{
    private $order;

    private function __construct(Order $order)
    {
        $this->order = $order;
        return $this;
    }

    public static function for(Order $order): self
    {
        return new static($order);
    }

    public function notify()
    {
        if ($this->order) {
            $this->approvalNotify();
        }
    }

    private function isReadyToApproved(): bool
    {
        $changes = $this->order->getChanges();
        return array_key_exists('ready_to_approved', $changes) && $changes['ready_to_approved'] == 1;
    }

    private function isUnapprovedRequest(): bool
    {
        $changes = $this->order->getChanges();
        return array_key_exists('un_approve_request', $changes) &&
            $changes['un_approve_request'] &&
            $this->order->is_approve == 1;
    }

    private function approvalNotify()
    {
        $notificationData = [
            'job_no' => $this->order->job_no,
            'buyer_id' => $this->order->buyer_id,
            'factory_id' => $this->order->factory_id,
        ];

        $approvalPermittedUser = ApprovalPermittedUserService::for(Approval::ORDER_APPROVAL)
            ->setBuyer($this->order->buyer_id)
            ->setStep($this->order->step);

        if ($this->isUnapprovedRequest()) {
            $approvalType = 'unapprove';
            $notificationData['approval_type'] = $approvalType;
            $approvalPermittedUser->setApprovalType($approvalType);

            Notification::send($approvalPermittedUser->get(), new OrderApprovalNotification($notificationData));
        } else if ($this->isReadyToApproved()) {
            $approvalType = 'approve';
            $notificationData['approval_type'] = $approvalType;
            $approvalPermittedUser->setApprovalType($approvalType);

            Notification::send($approvalPermittedUser->get(), new OrderApprovalNotification($notificationData));
        }
    }
}
