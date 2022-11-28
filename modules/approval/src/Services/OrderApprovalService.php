<?php

namespace SkylarkSoft\GoRMG\Approval\Services;

use Illuminate\Support\Facades\Notification;
use SkylarkSoft\GoRMG\Approval\Models\Approval;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Notifications\OrderApprovalNotification;
use SkylarkSoft\GoRMG\Merchandising\Services\Approval\ApprovalPermittedUserService;

class OrderApprovalService extends PriorityService
{

    public function response()
    {
        if (!$this->getLastPriority()) {
            return [];
        }

        return Order::query()
            ->with(['buyer', 'factory'])
            ->where('cancel_status', 0)
            ->where('factory_id', $this->getRequest()->get('factory_id'))
            ->when($this->getRequest()->get('buyer_id'), function ($query) {
                $query->where('buyer_id', $this->getRequest()->get('buyer_id'));
            })
            ->when($this->getRequest()->get('approval_type') == self::APPROVED,
                function ($query) {
                    $query->where('step', $this->getStep());
                },
                function ($query) {
                    $query->where('ready_to_approved', self::YES)
                        ->where('step', $this->getPreviousStep())
                        ->where(function ($query) {
                            $query->orWhere('is_approve', self::NO)
                                ->orWhere('is_approve', null);
                        });
                })
            ->when($this->getRequest()->get('unique_id'), function ($query) {
                $query->where('job_no', $this->getRequest()->get('unique_id'));
            })
            ->when($this->getRequest()->get('style_name'), function ($query) {
                $query->where('style_name', $this->getRequest()->get('style_name'));
            })
            ->when($this->getRequest()->get('year'), function ($query) {
                $query->whereYear('created_at', $this->getRequest()->get('year'));
            })
            ->when($this->getRequest()->get('from_date') && $this->getRequest()->get('to_date'), function ($query) {
                $query->whereDate('created_at', '>=', $this->getRequest()->get('from_date'))
                    ->whereDate('created_at', '<=', $this->getRequest()->get('to_date'));
            })
            ->get([
                'id', 'factory_id', 'buyer_id', 'style_name', 'job_no',
                'created_at', 'is_approve', 'rework_status', 'pcd_date',
                'pcd_remarks', 'ie_remarks'
            ]);
    }

    public function store()
    {
        $orders = Order::query()
            ->whereIn('job_no', $this->getRequest()->get('job_no'));

        if ($this->getStep() == $this->lastStep()) {
            $orders->update([
                'is_approve' => $this->getRequest()->get('approval_type') == 1 ?: null,
                'approve_date' => $this->getRequest()->get('approval_type') == 1 ? date('Y-m-d') : null,
            ]);
        }
        $orders->update([
            'step' => $this->getRequest()->get('approval_type') == 1 ? $this->getStep() : $this->getPreviousStep(),
            'approve_date' => $this->getRequest()->get('approval_type') == self::UNAPPROVED ? date('Y-m-d') : null,
        ]);

        $allPONo = $orders->with('purchaseOrders')
            ->get()
            ->pluck('purchaseOrders')
            ->collapse()
            ->pluck('po_no');

        if ($this->getStep() == $this->lastStep()) {
            (new PoApprovalUpdateService())
                ->approvePurchaseOrders(Approval::ORDER_APPROVAL, $allPONo, $this->getRequest()->get('approval_type'));
        } else if ($this->getRequest()->get('approval_type') == self::APPROVED) {
            (new PoApprovalUpdateService())
                ->unapprovePurchaseOrders(Approval::ORDER_APPROVAL, $allPONo);
        }

        $this->storeDetail($orders->get());
    }

    public function storeDetail($data)
    {
        $data->each(function ($order) {
            $approvalDetail = ApprovalDetailService::for(Approval::ORDER_APPROVAL)
                ->setPriority($order->step)
                ->setType($this->getRequest()->get('approval_type') == self::UNAPPROVED ? self::APPROVED : self::UNAPPROVED)
                ->setId($order->id)
                ->store();

            $this->notifyUser($approvalDetail, $order);
        });
    }

    private function notifyUser($approvalDetail, $data)
    {
        $approveType = $approvalDetail->type == self::APPROVED ? 'approve' : 'unapprove';
        $approvalPermittedUser = ApprovalPermittedUserService::for(Approval::ORDER_APPROVAL)
            ->setApprovalType($approveType)
            ->setBuyer($data->buyer_id)
            ->setStep($data->step)
            ->get();

        $notificationData = [
            'job_no' => $data->job_no,
            'buyer_id' => $data->buyer_id,
            'factory_id' => $data->factory_id,
            'approval_type' => $approveType,
        ];

        Notification::send($approvalPermittedUser, new OrderApprovalNotification($notificationData));
    }

    public function getUnapprovedData()
    {
        if (!$this->getLastPriority()) {
            return [];
        }
        return Order::query()
            ->where('is_approve', self::YES)
            ->whereNotNull('un_approve_request')
            ->with('buyer:id,name', 'createdBy:id,first_name,last_name')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'job_no' => $item->job_no,
                    'style_name' => $item->style_name,
                    'buyer' => $item->buyer->name,
                    'user' => $item->createdBy->first_name . ' ' . $item->createdBy->last_name,
                    'unapproved_request' => $item->un_approve_request,
                ];
            });
    }
}
