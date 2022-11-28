<?php

namespace SkylarkSoft\GoRMG\Approval\Services;


use Illuminate\Support\Facades\Notification;
use SkylarkSoft\GoRMG\Approval\Models\Approval;
use SkylarkSoft\GoRMG\Merchandising\Models\GatePassChallan\GatePasChallan;
use SkylarkSoft\GoRMG\Merchandising\Notifications\GatePassChallanApprovalNotification;
use SkylarkSoft\GoRMG\Merchandising\Notifications\OrderApprovalNotification;
use SkylarkSoft\GoRMG\Merchandising\Services\Approval\ApprovalPermittedUserService;
use Throwable;

class GatePassChallanApprovalService extends PriorityService
{


    public function response()
    {
        if (($this->getUserLastPriority())) {
            return GatePasChallan::query()
                ->with([
                    'party:id,name',
                    'factory:id,factory_name',
                    'department:id,product_department',
                ])
                ->approvalFilter($this->getRequest(), $this->getPreviousStep(), $this->getUserStep())
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'company' => $item->factory->factory_name ?? '',
                        'party' => $item->party->name ?? '',
                        'challan_no' => $item->challan_no ?? '',
                        'challan_date' => $item->challan_date ?? '',
                        'department' => $item->department->product_department ?? '',
                        'goods' => GatePasChallan::GOODS[$item->good_id] ?? '',
                    ];
                });
        }
        return [];

    }

    /**
     * @throws Throwable
     */
    public function store()
    {
        $query = GatePasChallan::query()
            ->whereIn('id', $this->getRequest()->get('gate_pass_ids'));

        $query->get()->map(function ($item) {
            $q = GatePasChallan::query()->where('id', $item->id);
            if ($q->first('approved_by')) {
                if ($this->getRequest()->get('type') == 1) {
                    $q->update([
                        'approved_by' => collect(json_decode($item->approved_by))->push(userId())
                    ]);
                } else {
                    $q->update([
                        'approved_by' => collect(json_decode($item->approved_by))->filter(function ($item) {
                            return $item != userId();
                        })
                    ]);
                }
            } else {
                $q->update([
                    'approved_by' => array(userId())
                ]);
            }
        });

        if ($this->getUserStep() == $this->lastPassStep()) {
            $query
                ->update([
                    'is_approve' => $this->getRequest()->get('type') == 1 ?: null,
                ]);
        }
        $query
            ->update([
                'step' => $this->getRequest()->get('type') == 1 ? $this->getUserStep() : $this->getUserStep() - 1,
            ]);

        $challans = $query->get();
        $this->storeDetail($challans);
    }

    /**
     * @throws Throwable
     */
    public function storeDetail($data)
    {
        $data->each(function ($challan) {
            ApprovalDetailService::for(Approval::GATE_PASS_CHALLAN_APPROVAL)
                ->setPriority($challan->step)
                ->setType($this->getRequest()->get('type') == self::UNAPPROVED ? self::APPROVED : self::UNAPPROVED)
                ->setId($challan->id)
                ->store();
//            $this->notifyUser($this->getRequest(), $challan);
        });
    }

    private function lastPassStep()
    {
        $priority = Approval::query()
            ->where([
                'factory_id' => factoryId(),
                'page_name' => 'Gate Pass Challan Approval'])
            ->get();
        $priorityList = collect($priority)->pluck('priority')->unique()->toArray();
        sort($priorityList);
        return collect($priorityList)->last();
    }

    private function getUserStep(): int
    {
        return $this->getUserLastPriority()->priority ?? 0;
    }

    private function getUserLastPriority()
    {
        return Approval::query()
            ->where([
                'factory_id' => factoryId(),
                'user_id' => userId(),
                'page_name' => 'Gate Pass Challan Approval'
            ])
            ->orWhere('alternative_user_id', userId())
            ->get()
            ->last();
    }

    private function notifyUser($approvalDetail, $data)
    {
        $approveType = $approvalDetail->type == self::APPROVED ? 'approve' : 'unapprove';
        $approvalPermittedUser = ApprovalPermittedUserService::for(Approval::GATE_PASS_CHALLAN_APPROVAL)
            ->setApprovalType($approveType)
            ->setBuyer($data->buyer_id)
            ->setStep($data->step)
            ->get();

        $notificationData = [
            'challan_no' => $data->challan_no,
            'buyer_id' => $data->buyer_id,
            'factory_id' => $data->factory_id,
            'approval_type' => $approveType,
        ];

        Notification::send($approvalPermittedUser, new GatePassChallanApprovalNotification($notificationData));
    }

    public function getUnapprovedData()
    {
        if (($this->getLastPriority())) {
            return GatePasChallan::query()
                ->with([
                    'party:id,name',
                    'factory:id,factory_name',
                    'department:id,product_department',
                ])
                ->where('is_approve', 1)
                ->whereNotNull('unapprove_request')
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'company' => $item->factory->factory_name ?? '',
                        'party' => $item->party->name ?? '',
                        'challan_no' => $item->challan_no ?? '',
                        'challan_date' => $item->challan_date ?? '',
                        'department' => $item->department->product_department ?? '',
                        'unapprove_request' => $item->unapprove_request,
                        'goods' => GatePasChallan::GOODS[$item->good_id] ?? '',
                    ];
                });
        }
        return [];
    }
}
