<?php
/**
 * Created by PhpStorm.
 * User: siam
 * Date: 8/25/19
 * Time: 3:49 PM
 */

namespace SkylarkSoft\GoRMG\Merchandising\Handle\PurchaseOrder;

use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Merchandising\Handle\PurchaseOrder\Interfaces\PurchaseOrderInterface;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderRatioBreakdown;

class PurchaseOrderStore implements PurchaseOrderInterface
{
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        try {
            DB::beginTransaction();
            // insert into purchase order table
            $id = $this->request->duplicate_mode ? null : $this->request->id;
            $purchase_order = PurchaseOrder::findOrNew($id);
            $purchase_order->buyer_id = $this->request->buyer_id;
            $purchase_order->order_id = $this->request->order_id;
            $purchase_order->po_no = $this->request->po_no;

            $purchase_order->print = $this->request->print;
            $purchase_order->embroidery = $this->request->embroidery;

            $purchase_order->shipping_mode = $this->request->shipping_mode;
            $purchase_order->packing_mode = implode(',', $this->request->packing_mode);
            $purchase_order->po_quantity = $this->request->po_quantity;
            $purchase_order->smv = $this->request->smv;
            $purchase_order->incoterm_id = $this->request->incoterm_id;
            $purchase_order->incoterm_place_id = $this->request->incoterm_place_id;
            $purchase_order->ex_factory_date = date('Y-m-d', strtotime($this->request->ex_factory_date));
            $purchase_order->order_uom = $this->request->order_uom;
            $purchase_order->save();

            /* get all color size breakdown if duplicate mode  */
            if ($this->request->duplicate_mode) {
                $this->clone_color_size_breakdown_for_duplicate_mode($this->request->id, $purchase_order->id);
            }
            // insert into purchase order ratio
            if ($this->request->order_uom == 2) {
                if (isset($request->id)) {
                    PurchaseOrderRatioBreakdown::where('purchase_order_id', $this->request->id)->delete();
                }
                foreach ($this->request->ratio as $key => $value) {
                    $ratio_breakdown = new PurchaseOrderRatioBreakdown();
                    $ratio_breakdown->purchase_order_id = $purchase_order->id;
                    $ratio_breakdown->item_id = $this->request->item_id[$key];
                    $ratio_breakdown->ratio = $this->request->ratio[$key];
                    $ratio_breakdown->save();
                }
            } else {
                PurchaseOrderRatioBreakdown::where('purchase_order_id', $this->request->id)->delete();
            }
            DB::commit();

            return $purchase_order->id;
        } catch (\Exception $e) {
            DB::rollback();

            return false;
        }
    }

    private function clone_color_size_breakdown_for_duplicate_mode($existing_purchase_order_id, $new_purchase_order_id)
    {
        $new_breakdown_array = [];
        $all_color_size_breakdown = PurchaseOrderDetail::where('purchase_order_id', $existing_purchase_order_id)->get();
        foreach ($all_color_size_breakdown as $key => $value) {
            $new_breakdown_array[$key]['purchase_order_id'] = $new_purchase_order_id;
            $new_breakdown_array[$key]['item_id'] = $value->item_id;
            $new_breakdown_array[$key]['color_id'] = $value->color_id;
            $new_breakdown_array[$key]['size_id'] = $value->size_id;
            $new_breakdown_array[$key]['country_id'] = $value->country_id;
            $new_breakdown_array[$key]['factory_id'] = factoryId();
            $new_breakdown_array[$key]['gsm'] = $value->gsm;
            $new_breakdown_array[$key]['quantity'] = $value->quantity;
            $new_breakdown_array[$key]['color_type'] = $value->color_type;
            $new_breakdown_array[$key]['fabric_description'] = $value->fabric_description;
            $new_breakdown_array[$key]['fabrication'] = $value->fabrication;
            $new_breakdown_array[$key]['composition_fabric_id'] = $value->composition_fabric_id;
            $new_breakdown_array[$key]['created_by'] = userId();
        }
        PurchaseOrderDetail::insert($new_breakdown_array);

        return true;
    }
}
