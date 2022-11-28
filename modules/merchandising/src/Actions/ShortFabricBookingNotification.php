<?php

namespace SkylarkSoft\GoRMG\Merchandising\Actions;

use Illuminate\Support\Facades\Notification;
use SkylarkSoft\GoRMG\Approval\Models\Approval;
use SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings\ShortFabricBooking;
use SkylarkSoft\GoRMG\Merchandising\Notifications\ShortFabricBookingApprovalNotification;
use SkylarkSoft\GoRMG\Merchandising\Services\Approval\ApprovalPermittedUserService;

class ShortFabricBookingNotification
{
    public static function send($booking)
    {
        $changes = $booking->getChanges();
        $isChangedForReadyToApproved = array_key_exists('ready_to_approved', $changes) && $changes['ready_to_approved'] == '1';
        $isChangedForUnapprovedRequest = array_key_exists('un_approve_request', $changes) &&
            $changes['un_approve_request'] != '' &&
            $booking->is_approved == ShortFabricBooking::APPROVED;

        $notificationData = [
            'buyer_id' => $booking->buyer_id,
            'unique_id' => $booking->unique_id,
            'factory_id' => $booking->factory_id,
        ];

        if ($isChangedForUnapprovedRequest) {
            $approvalType = 'unapprove';
            $notificationData['approval_type'] = $approvalType;
            (new static())->notifyUser($booking, $notificationData);
        }

        if ($isChangedForReadyToApproved) {
            $approvalType = 'approve';
            $notificationData['approval_type'] = $approvalType;
            (new static())->notifyUser($booking, $notificationData);
        }
    }

    public function notifyUser($booking, $notificationData)
    {
        $approvalPermittedUser = ApprovalPermittedUserService::for(Approval::SHORT_FABRIC_APPROVAL)
            ->setApprovalType($notificationData['approval_type'])
            ->setBuyer($booking->buyer_id)
            ->setStep($booking->step)
            ->get();

        Notification::send($approvalPermittedUser, new ShortFabricBookingApprovalNotification($notificationData));
    }
}
