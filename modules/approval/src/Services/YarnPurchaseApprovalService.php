<?php

namespace SkylarkSoft\GoRMG\Approval\Services;

use SkylarkSoft\GoRMG\Approval\Models\Approval;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseRequisitions\YarnPurchaseRequisition;
use SkylarkSoft\GoRMG\Merchandising\Models\WorkOrders\EmbellishmentWorkOrder;
use SkylarkSoft\GoRMG\Merchandising\Models\YarnPurchaseOrder;

class YarnPurchaseApprovalService extends PriorityService
{


    public function response()
    {

//        return $this->getBuyerList();
//        if (($this->getLastPriority())) {
            return YarnPurchaseRequisition::query()
//                ->with([
//                    'factory:id,group_name',
//                    'buyer:id,name', 'supplier:id,name',
//                ])
//                ->whereIn('buyer_id', $this->getBuyerList())
                ->approvalFilter($this->getRequest(), $this->getPreviousStep(), $this->getStep())
                ->get();
//        }
        return [];

    }

    public function store()
    {
        $requisitions = YarnPurchaseRequisition::query()
            ->whereIn('id', $this->getRequest()->get('bookings_id'));

        if ($this->getStep() == $this->lastStep()) {
            $requisitions->update([
                    'is_approved' => $this->getRequest()->get('type') == 1 ?: null,
                ]);
        }
        $requisitions->update([
                'step' => $this->getRequest()->get('type') == 1 ? $this->getStep() : $this->getPreviousStep(),
            ]);

        $requisitions = $requisitions->get();
        $this->storeDetail($requisitions);
    }

    public function storeDetail($data)
    {
        $data->each(function ($requisition) {
            ApprovalDetailService::for(Approval::YARN_PURCHASE_APPROVAL)
                ->setPriority($requisition->step)
                ->setType($this->getRequest()->get('type') == self::UNAPPROVED ? self::APPROVED : self::UNAPPROVED)
                ->setId($requisition->id)
                ->store();
        });
    }

    public function getUnapprovedData()
    {
        if ($this->getLastPriority()){
            return YarnPurchaseOrder::query()
                ->with(['buyer:id,name', 'createdBy:id,first_name,last_name', 'supplier:id,name','bookingDetails'])
                ->where('is_approved', 1)
                ->whereNotNull('unapproved_request')
                ->get()
                ->map(function ($booking) {
                    return [
                        'id' => $booking->id,
                        'booking_no' => $booking->requisition_no,
                        'uniq_id' => collect($booking->bookingDetails)->pluck('budget_unique_id')->unique()->implode(', '),
                        'style_name' => collect($booking->bookingDetails)->pluck('style')->unique()->implode(', '),
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
