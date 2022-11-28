<?php

namespace SkylarkSoft\GoRMG\Approval\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Approval\Models\Approval;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintInventoryChallan;

class PrintSendChallanCutManagerService
{
    protected $request;

    public function getRequest()
    {
        return $this->request;
    }


    public function setRequest(Request $request)
    {
        $this->request = $request;
        return $this;
    }

    public function response()
    {
        if ($this->getUserLastPriority()) {
            return PrintInventoryChallan::query()
                ->with([
                    'printFactory:id,factory_name,factory_address',
                    'part:id,name',
                ])
                ->approvalFilter($this->getRequest(), $this->getUserStep())
                ->orderBy('id', 'desc')
                ->paginate();
        }
        return [];
    }

    public function store()
    {
        $requestData = $this->getRequest();
        
        PrintInventoryChallan::query()
            ->whereIn('id', $requestData->get('challan_ids'))
            ->get()
            ->map(function ($item) use($requestData) {
                $approvalType = $requestData->get('approval_type');
                $approval = $approvalType == 1 ? 1 : 0;
                $cut_manager_approval_status = $approval;
                $cut_manager_approval_steps = $approval;
                $cut_manager_approved_id = $approval ? userId() : null;
                DB::table('print_inventory_challans')
                    ->where('id', $item->id)
                    ->update([
                        'cut_manager_approval_status' => $cut_manager_approval_status,
                        'cut_manager_approval_steps' => $cut_manager_approval_steps,
                        'cut_manager_approved_id' => $cut_manager_approved_id,
                    ]);
            });
    }

    private function getUserLastPriority()
    {
        return Approval::query()
            ->where([
                'factory_id' => factoryId(),
                'page_name' => 'Print Send Challan Approval(Cutting Manager)'
            ])
            ->where(function ($query) {
                $query->where('user_id', userId())
                    ->orWhere('alternative_user_id', userId());
            })
            ->orderBy('id', 'desc')
            ->first();
    }

    private function getUserStep(): int
    {
        return $this->getUserLastPriority()->priority ?? 0;
    }
}
