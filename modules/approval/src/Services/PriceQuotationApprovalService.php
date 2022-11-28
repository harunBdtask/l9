<?php

namespace SkylarkSoft\GoRMG\Approval\Services;

use Illuminate\Support\Facades\Notification;
use SkylarkSoft\GoRMG\Approval\Models\Approval;
use SkylarkSoft\GoRMG\Merchandising\Models\PriceQuotation;
use SkylarkSoft\GoRMG\Merchandising\Notifications\FabricBookingApprovalNotification;
use SkylarkSoft\GoRMG\Merchandising\Notifications\PriceQuotationApprovalNotification;
use SkylarkSoft\GoRMG\Merchandising\Services\Approval\ApprovalPermittedUserService;
use Throwable;

class PriceQuotationApprovalService extends PriorityService
{

    public function response()
    {
        if (($this->getLastPriority())) {
            return PriceQuotation::query()
                ->with(['factory', 'buyer', 'createdBy'])
                ->where('cancel_status', 0)
                ->whereIn('buyer_id', $this->getBuyerList())
                ->approvalFilter($this->getRequest(), $this->getPreviousStep(), $this->getStep())
                ->get();
        }
        return [];

    }

    public function store()
    {
        $priceQuotations = PriceQuotation::query()
            ->whereIn('quotation_id', $this->getRequest()->get('quotation_id'));

        if ($this->getStep() == $this->lastStep()) {
            $priceQuotations->update([
                'is_approve' => $this->getRequest()->get('type') == 1 ?: null,
            ]);
        }
        $priceQuotations->update([
            'step' => $this->getRequest()->get('type') == 1 ? $this->getStep() : $this->getPreviousStep(),
        ]);

        $priceQuotations = $priceQuotations->get();
        $this->storeDetail($priceQuotations);
    }

    /**
     * @throws Throwable
     */
    public function storeDetail($data)
    {
        $data->each( function ($priceQuotation) {
            $approvalDetail = ApprovalDetailService::for(Approval::PRICE_QUOTATION)
                ->setPriority($priceQuotation->step)
                ->setType($this->getRequest()->get('type') == self::UNAPPROVED ? self::APPROVED : self::UNAPPROVED)
                ->setId($priceQuotation->id)
                ->store();

            $this->notifyUser($approvalDetail, $priceQuotation);
        });
    }

    public function notifyUser($approvalDetail, $data)
    {
        $approveType = $approvalDetail->type == self::APPROVED ? 'approve' : 'unapprove';
        $approvalPermittedUser = ApprovalPermittedUserService::for(Approval::PRICE_QUOTATION)
            ->setApprovalType($approveType)
            ->setBuyer($data->buyer_id)
            ->setStep($data->step)
            ->get();

        $notificationData = [
            'buyer_id' => $data->buyer_id,
            'unique_id' => $data->quotation_id,
            'factory_id' => $data->factory_id,
            'approval_type' => $approveType,
        ];

        Notification::send($approvalPermittedUser, new PriceQuotationApprovalNotification($notificationData));
    }

    public function getUnapprovedData()
    {
        if ($this->getLastPriority()) {
            return PriceQuotation::query()->where('is_approve', 1)->whereNotNull('unapproved_request')
                ->with('buyer:id,name', 'createdBy:id,first_name,last_name')
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'quotation_no' => $item->quotation_id,
                        'buyer' => $item->buyer->name,
                        'user' => $item->createdBy->first_name . ' ' . $item->createdBy->last_name,
                        'unapproved_request' => $item->unapproved_request,
                    ];
                });
        }
        return [];
    }
}
