<?php

namespace SkylarkSoft\GoRMG\Approval\Services;

use Illuminate\Support\Facades\Notification;
use SkylarkSoft\GoRMG\Approval\Models\Approval;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBooking;
use SkylarkSoft\GoRMG\Merchandising\Notifications\FabricBookingReadyForApprovalNotification;
use SkylarkSoft\GoRMG\Merchandising\Notifications\FabricBookingUnApprovalRequestNotification;
use SkylarkSoft\GoRMG\Merchandising\Notifications\TrimsBookingApprovalNotification;
use SkylarkSoft\GoRMG\Merchandising\Services\Approval\ApprovalPermittedUserService;
use Throwable;

class TrimsBookingApprovalService extends PriorityService
{


    public function response()
    {

        if (($this->getLastPriority())) {
            return TrimsBooking::query()
                ->with([
                    'factory:id,group_name',
                    'buyer:id,name', 'supplier:id,name',
                    'details.budget'
                ])
                ->where('cancel_status', 0)
                ->whereIn('buyer_id', $this->getBuyerList())
                ->approvalFilter($this->getRequest(), $this->getPreviousStep(), $this->getStep())
                ->get();
        }
        return [];

    }

    public function store()
    {
        $bookings = TrimsBooking::query()
            ->whereIn('id', $this->getRequest()->get('bookings_id'));

        if ($this->getStep() == $this->lastStep()) {
            $bookings->update([
                'is_approve' => $this->getRequest()->get('type') == 1 ?: null,
            ]);
        }
        $bookings->update([
            'step' => $this->getRequest()->get('type') == 1 ? $this->getStep() : $this->getPreviousStep(),
        ]);

        $bookings = $bookings->get();
        $this->storeDetail($bookings);
    }

    /**
     * @throws Throwable
     */
    public function storeDetail($data)
    {
        $data->each(function ($booking) {
            $approvalDetail = ApprovalDetailService::for(Approval::TRIMS_APPROVAL)
                ->setPriority($booking->step)
                ->setType($this->getRequest()->get('type') == self::UNAPPROVED ? self::APPROVED : self::UNAPPROVED)
                ->setId($booking->id)
                ->store();

            $this->notifyUser($approvalDetail, $booking);
        });

    }

    public function notifyUser($approvalDetail, $data)
    {
        $approveType = $approvalDetail->type == self::APPROVED ? 'approve' : 'unapprove';
        $approvalPermittedUser = ApprovalPermittedUserService::for(Approval::TRIMS_APPROVAL)
            ->setApprovalType($approveType)
            ->setBuyer($data->buyer_id)
            ->setStep($data->step)
            ->get();

        $notificationData = [
            'buyer_id' => $data->buyer_id,
            'unique_id' => $data->unique_id,
            'factory_id' => $data->factory_id,
            'approval_type' => $approveType,
        ];

        Notification::send($approvalPermittedUser, new TrimsBookingApprovalNotification($notificationData));
    }

    public function getUnapprovedData()
    {
        if ($this->getLastPriority()) {
            return TrimsBooking::query()
                ->with(['buyer:id,name', 'createdBy:id,first_name,last_name', 'supplier:id,name'])
                ->where('is_approve', 1)
                ->whereNotNull('un_approve_request')
                ->get()
                ->map(function ($booking) {
                    return [
                        'id' => $booking->id,
                        'booking_no' => $booking->unique_id,
                        'uniq_id' => $booking->budget_job_no,
                        'style_name' => $booking->style_name,
                        'buyerName' => $booking->buyer->name,
                        'supplierName' => $booking->supplier->name,
                        'userName' => $booking->createdBy->full_name,
                        'unapproved_request' => $booking->un_approve_request,

                    ];
                });
        }
        return [];
    }
}