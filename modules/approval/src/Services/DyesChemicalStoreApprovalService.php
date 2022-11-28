<?php

namespace SkylarkSoft\GoRMG\Approval\Services;


use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Approval\Models\Approval;
use SkylarkSoft\GoRMG\DyesStore\Models\DyesChemicalsReceive;
use SkylarkSoft\GoRMG\DyesStore\Models\DyesChemicalTransaction;
use SkylarkSoft\GoRMG\DyesStore\Services\StockTransactionService;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class DyesChemicalStoreApprovalService extends PriorityService
{
    public function response()
    {
        if (($this->getUserLastPriority())) {
            return DyesChemicalsReceive::query()
                ->with('supplier:id,name')
                ->approvalFilter($this->getRequest(), $this->getPreviousStep(), $this->getUserStep())
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'supplier_name' => $item->supplier->name ?? '',
                        'receive_no' => $item->system_generate_id,
                        'reference_no' => $item->reference_no,
                        'lc_no' => $item->lc_no,
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

        $query = DyesChemicalsReceive::query()
            ->whereIn('id', $this->getRequest()->get('dyes_chemical_receive_ids'));

        $stockQuery = DyesChemicalTransaction::query()->whereIn('dyes_chemical_receive_id', $this->getRequest()->get('dyes_chemical_receive_ids'));

        if ($this->getRequest()->get('type') != 1) {
            $stockData = $stockQuery->get();

            if ($stockData->whereNotNull('dyes_chemical_issue_id')->count() > 0) {
                throw new \Exception('Can not unapproved this item!', Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }

        $query->get()->map(function ($item) use ($stockQuery) {
            $q = DyesChemicalsReceive::query()
                ->where('id', $item->id);

            if ($q->first()->approved_by ?? '') {
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
                    'readonly' => $this->getRequest()->get('type') == 1 ? 0 : 1,
                    'is_approve' => $this->getRequest()->get('type') == 1 ?: null,
                    'approve_date' => $this->getRequest()->get('type') == 1 ? date('Y-m-d') : null,
                    'ready_to_approve' => $this->getRequest()->get('type') == 1 ?: 0
                ]);

            if ($this->getRequest()->get('type') == 1) {
                $query->get()->map(function ($item) {
                    (new StockTransactionService('in', $item->id))->handle();
                });
            } else {
                $stockQuery->delete();
            }
        }
        $query
            ->update([
                'step' => $this->getRequest()->get('type') == 1 ? $this->getUserStep() : $this->getUserStep() - 1
            ]);

        $this->storeDetail($query->get());

        DB::commit();
    }

    /**
     * @throws Throwable
     */
    public function storeDetail($data)
    {
        $data->each(function ($challan) {
            ApprovalDetailService::for(Approval::DYES_CHEMICAL_STORE_APPROVAL)
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
                'page_name' => Approval::DYES_CHEMICAL_STORE_APPROVAL])
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
                'page_name' => Approval::DYES_CHEMICAL_STORE_APPROVAL])
            ->get()
            ->last();
    }


    public function getUnapprovedData()
    {
        if (($this->getLastPriority())) {
            return DyesChemicalsReceive::query()
                ->with('supplier:id,name')
                ->where('is_approve', 1)
                ->whereNotNull('un_approve_request')
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'supplier_name' => $item->supplier->name ?? '',
                        'receive_no' => $item->system_generate_id,
                        'reference_no' => $item->reference_no,
                        'lc_no' => $item->lc_no,
                        'receive_date' => $item->receive_date,
                        'un_approve_request' => $item->un_approve_request,
                    ];
                });
        }
        return [];
    }
}
