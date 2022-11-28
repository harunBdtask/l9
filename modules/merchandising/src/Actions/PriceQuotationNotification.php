<?php

namespace SkylarkSoft\GoRMG\Merchandising\Actions;

use Google\Service\AdExchangeBuyer\Price;
use Illuminate\Support\Facades\Notification;
use SkylarkSoft\GoRMG\Approval\Models\Approval;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\PriceQuotation;
use SkylarkSoft\GoRMG\Merchandising\Notifications\FabricBookingReadyForApprovalNotification;
use SkylarkSoft\GoRMG\Merchandising\Notifications\FabricBookingUnApprovalRequestNotification;
use SkylarkSoft\GoRMG\Merchandising\Notifications\PriceQuotationApprovalNotification;
use SkylarkSoft\GoRMG\Merchandising\Notifications\TrimsBookingApprovalNotification;
use SkylarkSoft\GoRMG\Merchandising\Services\Approval\ApprovalPermittedUserService;

class PriceQuotationNotification
{
    public static function send($priceQuotation)
    {
        $changes = $priceQuotation->getChanges();
        $isChangedForReadyToApproved = array_key_exists('ready_to_approve', $changes) && $changes['ready_to_approve'] == '1';
        $isChangedForUnapprovedRequest = array_key_exists('unapproved_request', $changes) &&
            $changes['unapproved_request'] != '' &&
            $priceQuotation->is_approve == PriceQuotation::APPROVED;

        $notificationData = [
            'unique_id' => $priceQuotation->quotation_id,
            'buyer_id' => $priceQuotation->buyer_id,
            'factory_id' => $priceQuotation->factory_id,
        ];

        if ($isChangedForUnapprovedRequest) {
            $approvalType = 'unapprove';
            $notificationData['approval_type'] = $approvalType;
            (new static())->notifyUser($priceQuotation, $notificationData);
        }

        if ($isChangedForReadyToApproved) {
            $approvalType = 'approve';
            $notificationData['approval_type'] = $approvalType;
            (new static())->notifyUser($priceQuotation, $notificationData);
        }
    }

    public function notifyUser($priceQuotation, $notificationData)
    {
        $approvalPermittedUser = ApprovalPermittedUserService::for(Approval::PRICE_QUOTATION)
            ->setApprovalType($notificationData['approval_type'])
            ->setBuyer($priceQuotation->buyer_id)
            ->setStep($priceQuotation->step)
            ->get();
        Notification::send($approvalPermittedUser, new PriceQuotationApprovalNotification($notificationData));
    }
}
