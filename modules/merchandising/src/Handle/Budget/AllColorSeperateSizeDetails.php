<?php


namespace SkylarkSoft\GoRMG\Merchandising\Handle\Budget;

use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Merchandising\Models\BudgetMaster;
use SkylarkSoft\GoRMG\Merchandising\Models\BudgetTrimsAccessoricsComponentsDetails;
use SkylarkSoft\GoRMG\Merchandising\Models\OrderColorBreakdown;
use SkylarkSoft\GoRMG\Merchandising\Models\OrderSizeBreakdown;

class AllColorSeperateSizeDetails
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

            $orderId = BudgetMaster::findOrFail($this->request->budgets_id)->order_id;
            $colors = OrderColorBreakdown::with('color')->where('order_id', $orderId)->get()->pluck('color.name', 'color.id')->toArray();

            BudgetTrimsAccessoricsComponentsDetails::where(['trims_budget_id' => $this->trimsId->id, 'budget_id' => $this->request->budgets_id, 'item_id' => $this->request->item_id])->delete();
            foreach ($colors  as $key1 => $value1) {
                foreach ($this->request->map as $key2 => $value2) {
                    $total_quantity_for_all_color = $value2['quantity'];
                    $extra_percentage = $value2['size_wise_percentage'];

                    $color_wise_percentage = ($extra_percentage / (count($colors)));
                    $total_quantity_with_percentage_color_wise = $total_quantity_for_all_color + (($total_quantity_for_all_color * $value2['size_wise_percentage']) / 100);
                    $individual_color_wise_quantity = ($total_quantity_with_percentage_color_wise / (count($colors)));

                    $data = [];
                    $data['trims_budget_id'] = $this->trimsId->id;
                    $data['budget_id'] = $this->request->budgets_id;
                    $data['item_id'] = $this->request->item_id;
                    $data['buyer_id'] = $this->request->buyer_id;
                    $data['color_type'] = $this->request->color_type;
                    $data['size_type'] = $this->request->size_type;
                    $data['color_id'] = $key1;
                    $data['size_id'] = $key2;
                    $data['quantity'] = $individual_color_wise_quantity;
                    $data['extra_percentage_by_size'] = $color_wise_percentage;
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
