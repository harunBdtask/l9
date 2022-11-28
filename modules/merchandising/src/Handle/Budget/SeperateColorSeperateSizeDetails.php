<?php


namespace SkylarkSoft\GoRMG\Merchandising\Handle\Budget;

use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Merchandising\Models\BudgetTrimsAccessoricsComponentsDetails;

class SeperateColorSeperateSizeDetails
{
    protected $request;
    protected $trimsId;

    public function __construct($request, $trimsId)
    {
        $this->request = $request;
        $this->trimsId = $trimsId;
    }

    public function storeData()
    {
        try {
            DB::beginTransaction();
            BudgetTrimsAccessoricsComponentsDetails::where(['trims_budget_id' => $this->trimsId->id, 'budget_id' => $this->request->budgets_id, 'item_id' => $this->request->item_id])->delete();
            foreach ($this->request->map as $key1 => $value1) {
                foreach ($value1 as $key2 => $value2) {
                    $data = [];
                    $data['trims_budget_id'] = $this->trimsId->id;
                    $data['budget_id'] = $this->request->budgets_id;
                    $data['item_id'] = $this->request->item_id;
                    $data['buyer_id'] = $this->request->buyer_id;
                    $data['color_type'] = $this->request->color_type;
                    $data['size_type'] = $this->request->size_type;
                    $data['color_id'] = $key1;
                    $data['size_id'] = $key2;
                    $data['quantity'] = $value2['quantity'];
                    $data['extra_percentage_by_size'] = $value2['size_wise_percentage'] ?? 0;
                    BudgetTrimsAccessoricsComponentsDetails::create($data);
                }
            }
            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollback();

            return false;
        }
    }
}
