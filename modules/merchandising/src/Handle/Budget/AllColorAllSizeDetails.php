<?php


namespace SkylarkSoft\GoRMG\Merchandising\Handle\Budget;

use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Merchandising\Models\BudgetMaster;
use SkylarkSoft\GoRMG\Merchandising\Models\BudgetTrimsAccessoricsComponentsDetails;
use SkylarkSoft\GoRMG\Merchandising\Models\OrderColorBreakdown;
use SkylarkSoft\GoRMG\Merchandising\Models\OrderSizeBreakdown;

class AllColorAllSizeDetails
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
            $sizes = OrderSizeBreakdown::with('size')->where('order_id', $orderId)->get()->pluck('size.name', 'size.id')->toArray();

            $total_no_of_combination = (count($colors) * count($sizes));

            $total_quantity = $this->request->map['all_color_size_qty'];
            $extra_percentage = $this->request->extra_percentage;

            $total_quantity_with_percentage = $total_quantity + (($total_quantity * $extra_percentage) / 100);
            $per_color_amount = ($total_quantity_with_percentage / count($colors));
            $per_size_amount = ($per_color_amount / (count($sizes)));


            BudgetTrimsAccessoricsComponentsDetails::where(['trims_budget_id' => $this->trimsId->id, 'budget_id' => $this->request->budgets_id, 'item_id' => $this->request->item_id])->delete();

            foreach ($colors  as $key1 => $value1) {
                foreach ($sizes as $key2 => $value2) {
                    $data = [];
                    $data['trims_budget_id'] = $this->trimsId->id;
                    $data['budget_id'] = $this->request->budgets_id;
                    $data['item_id'] = $this->request->item_id;
                    $data['buyer_id'] = $this->request->buyer_id;
                    $data['color_type'] = $this->request->color_type;
                    $data['size_type'] = $this->request->size_type;
                    $data['color_id'] = $key1;
                    $data['size_id'] = $key2;
                    $data['quantity'] = $per_size_amount ;
                    $data['extra_percentage_by_size'] = ($extra_percentage / $total_no_of_combination);
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
