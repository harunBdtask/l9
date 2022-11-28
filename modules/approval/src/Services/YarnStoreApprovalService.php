<?php

namespace SkylarkSoft\GoRMG\Approval\Services;


use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Approval\Models\Approval;
use SkylarkSoft\GoRMG\Inventory\Models\YarnReceive;
use SkylarkSoft\GoRMG\Inventory\Services\YarnReceive\YarnReceiveStockService;
use SkylarkSoft\GoRMG\Inventory\Services\YarnStockSummaryService;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class YarnStoreApprovalService extends PriorityService
{
    public function response()
    {
        if (($this->getUserLastPriority())) {
            return YarnReceive::query()
                ->with([
                    'store:id,name',
                    'factory:id,factory_name',
                ])
                ->approvalFilter($this->getRequest(), $this->getPreviousStep(), $this->getUserStep())
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'company' => $item->factory->factory_name ?? '',
                        'store_name' => $item->store->name ?? '',
                        'challan_no' => $item->challan_no,
                        'receive_no' => $item->receive_no,
                        'receive_date' => $item->receive_date,
                        'approval_type' => $item->is_approve == 1 ? 'Approved' : 'Unapproved',
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
        DB::beginTransaction();

        $query = YarnReceive::query()
            ->withoutGlobalScope('approvalMaintain')
            ->whereIn('id', $this->getRequest()->get('yarn_receive_ids'));

        $receiveWithDetails = $query->with('details')->get();

        if ($this->getRequest()->get('type') != 1) {
            foreach ($receiveWithDetails as $receive) {
                foreach ($receive->details as $detail) {
                    $yarnStockSummary = (new YarnStockSummaryService())->summary($detail);
                    if (!($detail->receive_qty <= $yarnStockSummary->balance)) {
                        throw new \Exception('Can not unapproved this item!', Response::HTTP_UNPROCESSABLE_ENTITY);
                    }
                }
            }
        }

        $query->get()->map(function ($item) {
            $q = YarnReceive::query()
                ->withoutGlobalScope('approvalMaintain')
                ->where('id', $item->id);

            $approvedBy = $q->first()->approved_by ?? '';

            if ($approvedBy) {
                $approvedByCollection = collect(json_decode($item->approved_by));
                if ($this->getRequest()->get('type') == 1) {
                    $q->update([
                        'approved_by' => $approvedByCollection->push(userId())
                    ]);
                } else {
                    $q->update([
                        'approved_by' => $approvedByCollection->filter(function ($item) {
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
                    'approve_date' => date('Y-m-d')
                ]);

            $this->handleStockUpdate($query->get());

            if ($this->getRequest()->get('type') != 1) {
                foreach ($receiveWithDetails as $receive) {
                    foreach ($receive->details as $detail) {
                        (new YarnReceiveStockService())->deleted($detail);
                    }
                }
            }
        }
        $query
            ->update([
                'step' => $this->getRequest()->get('type') == 1 ? $this->getUserStep() : $this->getUserStep() - 1
            ]);

        $challans = $query->get();
        $this->storeDetail($challans);

        DB::commit();
    }

    /**
     * @throws Throwable
     */
    public function storeDetail($data)
    {
        $data->each(function ($challan) {
            ApprovalDetailService::for(Approval::YARN_STORE_APPROVAL)
                ->setPriority($challan->step)
                ->setType($this->getRequest()->get('type') == self::UNAPPROVED ? self::APPROVED : self::UNAPPROVED)
                ->setId($challan->id)
                ->store();
        });
    }

    private function lastPassStep()
    {
        $priority = Approval::query()
            ->where([
                'factory_id' => factoryId(),
                'page_name' => Approval::YARN_STORE_APPROVAL])
            ->get();
        $priorityList = $priority->pluck('priority')->unique()->toArray();
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
                'page_name' => Approval::YARN_STORE_APPROVAL])
            ->get()
            ->last();
    }


    public function getUnapprovedData()
    {
        if (($this->getLastPriority())) {
            return YarnReceive::query()
                ->with([
                    'factory:id,factory_name',
                    'store:id,name',
                ])
                ->where('is_approve', 1)
                ->whereNotNull('un_approve_request')
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'company' => $item->factory->factory_name ?? '',
                        'store_name' => $item->store->name ?? '',
                        'challan_no' => $item->challan_no,
                        'receive_no' => $item->receive_no,
                        'receive_date' => $item->receive_date,
                        'un_approve_request' => $item->un_approve_request,
                    ];
                });
        }
        return [];
    }

    private function handleStockUpdate($receives)
    {
        $receives->map(function ($item) {
            $item->load('details');
            collect($item->details)->map(function ($yarn) {
                (new YarnReceiveStockService())->created($yarn);
            });
        });
    }
}
