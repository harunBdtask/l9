<?php

namespace SkylarkSoft\GoRMG\Approval\Services;

use SkylarkSoft\GoRMG\Approval\Models\Approval;
use SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings\ShortTrimsBooking;

class ShortTrimsBookingApprovalService extends PriorityService
{


    public function response()
    {

        if (($this->getLastPriority())) {
            return ShortTrimsBooking::query()
                ->with([
                    'factory:id,group_name',
                    'buyer:id,name', 'supplier:id,name',
                    'details.budget'
                ])
                ->whereIn('buyer_id', $this->getBuyerList())
                ->approvalFilter($this->getRequest(), $this->getPreviousStep(), $this->getStep())
                ->get();
        }
        return [];

    }

    public function store()
    {
        $bookings = ShortTrimsBooking::query()
            ->whereIn('id', $this->getRequest()->get('bookings_id'));

        if ($this->getStep() == $this->lastStep()) {
            $bookings->update([
                    'is_approved' => $this->getRequest()->get('type') == 1 ?: null,
                ]);
        }
        $bookings->update([
                'step' => $this->getRequest()->get('type') == 1 ? $this->getStep() : $this->getPreviousStep(),
            ]);

        $trimsBookings = $bookings->get();
        $this->storeDetail($trimsBookings);
    }

    /**
     * @throws \Throwable
     */
    public function storeDetail($data)
    {
        $data->each(function ($booking) {
            ApprovalDetailService::for(Approval::SHORT_TRIMS_APPROVAL)
                ->setPriority($booking->step)
                ->setType($this->getRequest()->get('type') == self::UNAPPROVED ? self::APPROVED : self::UNAPPROVED)
                ->setId($booking->id)
                ->store();
        });
    }

    public function getUnapprovedData()
    {
        if ($this->getLastPriority()){
            return ShortTrimsBooking::query()
                ->with(['buyer:id,name', 'createdBy:id,first_name,last_name', 'supplier:id,name'])
                ->where('is_approved', 1)
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
