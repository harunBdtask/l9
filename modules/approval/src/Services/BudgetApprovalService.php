<?php

namespace SkylarkSoft\GoRMG\Approval\Services;

use Illuminate\Support\Facades\Notification;
use SkylarkSoft\GoRMG\Approval\Models\Approval;
use SkylarkSoft\GoRMG\Merchandising\Actions\BudgetNotification;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Merchandising\Notifications\BudgetReadyForApprovalNotification;
use SkylarkSoft\GoRMG\Merchandising\Notifications\BudgetUnApprovalRequestNotification;
use SkylarkSoft\GoRMG\Merchandising\Services\Approval\ApprovalPermittedUserService;
use Throwable;

class BudgetApprovalService extends PriorityService
{
    public function response()
    {
        if (($this->getLastPriority())) {
            return Budget::query()
                ->with('buyer:id,name', 'factory:id,factory_name', 'createdBy:id,first_name,last_name')
                ->where('cancel_status', 0)
                ->whereIn('buyer_id', $this->getBuyerList())
                ->approvalFilter($this->getRequest(), $this->getPreviousStep(), $this->getStep())
                ->get();
        }

        return [];

    }

    /**
     * @throws Throwable
     */
    public function store()
    {
        $budgets = Budget::query()
            ->whereIn('job_no', $this->getRequest()->get('job_no'));
        if ($this->getStep() == $this->lastStep()) {
            $budgets->update([
                'is_approve' => $this->getRequest()->get('type') == 1 ?: null,
                'approve_date' => $this->getRequest()->get('type') == 1 ? date('Y-m-d') : null,
            ]);
        }
        $budgets->update([
            'step' => $this->getRequest()->get('type') == 1 ? $this->getStep() : $this->getPreviousStep(),
            'approve_date' => $this->getRequest()->get('type') == 1 ? date('Y-m-d') : null,
        ]);

        $budgets = $budgets->get();
        $this->storeDetail($budgets);
    }
    /**
     * @throws Throwable
     */
    public function storeDetail($data)
    {
        $data->each(function ($budget) {
            $approvalDetail = ApprovalDetailService::for(Approval::BUDGET_APPROVAL)
                ->setPriority($budget->step)
                ->setType($this->getRequest()->get('type') == self::UNAPPROVED ? self::APPROVED : self::UNAPPROVED)
                ->setId($budget->id)
                ->store();

            $this->notifyUser($approvalDetail, $budget);
        });
    }

    public function notifyUser($approvalDetail, $data)
    {
        $approveType = $approvalDetail->type == self::APPROVED ? 'approve' : 'unapprove';
        $approvalPermittedUser = ApprovalPermittedUserService::for(Approval::BUDGET_APPROVAL)
            ->setApprovalType($approveType)
            ->setBuyer($data->buyer_id)
            ->setStep($data->step)
            ->get();

        $notificationData = [
            'job_no' => $data->job_no,
            'buyer_id' => $data->buyer_id,
            'factory_id' => $data->factory_id,
        ];

        if ($approveType == 'approve') {
            Notification::send($approvalPermittedUser, new BudgetReadyForApprovalNotification($notificationData));
        } else {
            Notification::send($approvalPermittedUser, new BudgetUnApprovalRequestNotification($notificationData));
        }
    }

    public function getUnapprovedData()
    {
        if (($this->getLastPriority())) {
            return Budget::query()->where('is_approve', 1)->whereNotNull('un_approve_request')
                ->with('buyer:id,name', 'createdBy:id,first_name,last_name')
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'quotation_no' => $item->job_no,
                        'buyer' => $item->buyer->name,
                        'user' => $item->createdBy->first_name . ' ' . $item->createdBy->last_name,
                        'unapproved_request' => $item->un_approve_request,
                    ];
                });
        }

        return [];

    }
}
