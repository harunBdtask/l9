<?php

namespace SkylarkSoft\GoRMG\Approval\Services;

use Illuminate\Support\Facades\Notification;
use SkylarkSoft\GoRMG\Approval\Models\Approval;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricServiceBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBooking;
use SkylarkSoft\GoRMG\Merchandising\Notifications\ServiceBookingApprovalNotification;
use SkylarkSoft\GoRMG\Merchandising\Notifications\ShortFabricBookingApprovalNotification;
use SkylarkSoft\GoRMG\Merchandising\Services\Approval\ApprovalPermittedUserService;
use Throwable;

class ServiceBookingApprovalService extends PriorityService
{


    public function response()
    {

//        return $this->getBuyerList();
        if (($this->getLastPriority())) {
            return FabricServiceBooking::query()
                ->with([
                    'factory:id,group_name',
                    'buyer:id,name', 'supplier:id,name',
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
        $services = FabricServiceBooking::query()
            ->whereIn('id', $this->getRequest()->get('bookings_id'));

        if ($this->getStep() == $this->lastStep()) {
            $services->update([
                    'is_approved' => $this->getRequest()->get('type') == 1 ?: null,
                ]);
        }
        $services->update([
                'step' => $this->getRequest()->get('type') == 1 ? $this->getStep() : $this->getPreviousStep(),
            ]);

        $services = $services->get();
        $this->storeDetail($services);
    }

    /**
     * @throws Throwable
     */
    public function storeDetail($data)
    {
        $data->each( function ($service) {
            $approvalDetail = ApprovalDetailService::for(Approval::SERVICE_APPROVAL)
                ->setPriority($service->step)
                ->setType($this->getRequest()->get('type') == self::UNAPPROVED ? self::APPROVED : self::UNAPPROVED)
                ->setId($service->id)
                ->store();

            $this->notifyUser($approvalDetail, $service);
        });
    }

    public function notifyUser($approvalDetail, $data)
    {
        $approveType = $approvalDetail->type == self::APPROVED ? 'approve' : 'unapprove';
        $approvalPermittedUser = ApprovalPermittedUserService::for(Approval::SHORT_FABRIC_APPROVAL)
            ->setApprovalType($approveType)
            ->setBuyer($data->buyer_id)
            ->setStep($data->step)
            ->get();

        $notificationData = [
            'buyer_id' => $data->buyer_id,
            'unique_id' => $data->booking_no,
            'factory_id' => $data->factory_id,
            'approval_type' => $approveType,
        ];

        Notification::send($approvalPermittedUser, new ServiceBookingApprovalNotification($notificationData));
    }

    public function getUnapprovedData()
    {
        if ($this->getLastPriority()){
            return FabricServiceBooking::query()
                ->with(['buyer:id,name', 'createdBy:id,first_name,last_name', 'supplier:id,name','details.budget'])
                ->where('is_approved', 1)
                ->whereNotNull('unapproved_request')
                ->get()
                ->map(function ($booking) {
                    return [
                        'id' => $booking->id,
                        'booking_no' => $booking->booking_no,
                        'uniq_id' => collect($booking->details)->pluck('budget.job_no')->unique()->implode(', '),
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
