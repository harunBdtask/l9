<?php

namespace SkylarkSoft\GoRMG\Merchandising\Actions;

use Illuminate\Support\Facades\Notification;
use SkylarkSoft\GoRMG\Approval\Models\Approval;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBooking;
use SkylarkSoft\GoRMG\Merchandising\Notifications\FabricBookingReadyForApprovalNotification;
use SkylarkSoft\GoRMG\Merchandising\Notifications\FabricBookingUnApprovalRequestNotification;
use SkylarkSoft\GoRMG\Merchandising\Notifications\ServiceBookingApprovalNotification;
use SkylarkSoft\GoRMG\Merchandising\Notifications\TrimsBookingApprovalNotification;
use SkylarkSoft\GoRMG\Merchandising\Services\Approval\ApprovalPermittedUserService;

class ServiceBookingNotification
{
    public static function send($booking)
    {
        $changes = $booking->getChanges();
        $isChangedForReadyToApproved = array_key_exists('ready_to_approve', $changes) && $changes['ready_to_approve'] == '1';
        $isChangedForUnapprovedRequest = array_key_exists('unapproved_request', $changes) &&
            $changes['unapproved_request'] != '' &&
            $booking->is_approved == FabricBooking::APPROVED;

        $notificationData = [
            'unique_id' => $booking->booking_no,
            'buyer_id' => $booking->buyer_id,
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
        $approvalPermittedUser = ApprovalPermittedUserService::for(Approval::SERVICE_APPROVAL)
            ->setApprovalType($notificationData['approval_type'])
            ->setBuyer($booking->buyer_id)
            ->setStep($booking->step)
            ->get();
        Notification::send($approvalPermittedUser, new ServiceBookingApprovalNotification($notificationData));
    }
}
