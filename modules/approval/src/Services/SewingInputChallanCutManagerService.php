<?php

namespace SkylarkSoft\GoRMG\Approval\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Approval\Models\Approval;
use SkylarkSoft\GoRMG\Inputdroplets\Models\CuttingInventoryChallan;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintInventoryChallan;

class SewingInputChallanCutManagerService
{
    protected $request;
    protected $challan = 'challan';

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

            $list =  CuttingInventoryChallan::with([
                    'line:id,line_no,floor_id',
                    'line.floor:id,floor_no',
                    'user:id,first_name,last_name,screen_name,email',
                    'user.factory:id,factory_name',
                    'color:id,name'
                ])
                ->where('type', $this->challan)
                ->approvalFilter($this->getRequest(), $this->getUserStep())
                ->orderBy('created_at', 'desc')
                ->paginate(15);

           $items =  collect($list->items())->map(function ($item) {
                
                $challan_originial_time = $item->updated_at;
                $new_challan_time = date('Y-m-d', strtotime($challan_originial_time)).' ';
                  if (date('H', strtotime($challan_originial_time)) < 8) {
                    $new_challan_time .= '08:'.date('i:s', strtotime($challan_originial_time));
                  } elseif (date('H', strtotime($challan_originial_time)) >= 19) {
                    $new_challan_time .= '18:'.date('i:s', strtotime($challan_originial_time));
                  } else {
                    $new_challan_time .= date('H:i:s', strtotime($challan_originial_time));
                  }
                $quantity = 0;
                $total_rejection = 0;
                $print_rejection = 0;
                $embr_rejection = 0;
                $item->cutting_inventory->groupBy('bundle_card_id')->each(function($item, $key) use(&$quantity, &$total_rejection, &$print_rejection, &$embr_rejection) {
                  if ($item->first()->bundlecard) {
                    $quantity += $item->first()->bundlecard->quantity;
                    $total_rejection += $item->first()->bundlecard->total_rejection;
                    $print_rejection += $item->first()->bundlecard->print_rejection;
                    $embr_rejection += $item->first()->bundlecard->embroidary_rejection;
                  }
                });
                
                $print_embroidary_rejection = $print_rejection >= $embr_rejection ? $print_rejection : $embr_rejection;

                $item->input_qty = $quantity - $total_rejection - $print_embroidary_rejection;
                $item->new_challan_time = $new_challan_time;
                return $item;
            });
            $list->setCollection($items);
            return $list;

        }
        return [];
    }

    public function store()
    {
        $requestData = $this->getRequest();
        
        CuttingInventoryChallan::query()
            ->whereIn('id', $requestData->get('challan_ids'))
            ->get()
            ->map(function ($item) use($requestData) {
                $approvalType = $requestData->get('approval_type');
                $approval = $approvalType == 1 ? 1 : 0;
                $cut_manager_approval_status = $approval;
                $cut_manager_approval_steps = $approval;
                $cut_manager_approved_id = $approval ? userId() : null;
                DB::table('cutting_inventory_challans')
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
                'page_name' => 'Sewing Input Challan Approval(Cutting Manager)'
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
