<?php

namespace SkylarkSoft\GoRMG\Approval\Services;

use SkylarkSoft\GoRMG\Approval\Models\Approval;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseRequisitions\YarnPurchaseRequisition;
use SkylarkSoft\GoRMG\Merchandising\Models\WorkOrders\EmbellishmentWorkOrder;
use SkylarkSoft\GoRMG\Merchandising\Models\YarnPurchaseOrder;

class YarnPurchaseOrderApprovalService extends PriorityService
{
    public function response()
    {
        if (($this->getLastPriority())) {
            return YarnPurchaseOrder::query()
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

    /**
     * @throws \Throwable
     */
    public function store()
    {
        $orders = YarnPurchaseOrder::query()
            ->whereIn('id', $this->getRequest()->get('bookings_id'));

        if ($this->getStep() == $this->lastStep()) {
            $orders->update([
                    'is_approved' => $this->getRequest()->get('type') == 1 ?: 0,
                ]);
        }
        $orders->update([
                'step' => $this->getRequest()->get('type') == 1 ? $this->getStep() : $this->getPreviousStep(),
            ]);

        $orders = $orders->get();
        $this->storeDetail($orders);
    }

    /**
     * @throws \Throwable
     */
    public function storeDetail($data)
    {
        $data->each(function ($order) {
            ApprovalDetailService::for(Approval::YARN_PURCHASE_APPROVAL)
                ->setPriority($order->step)
                ->setType($this->getRequest()->get('type') == self::UNAPPROVED ? self::APPROVED : self::UNAPPROVED)
                ->setId($order->id)
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
