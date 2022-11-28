<?php


namespace SkylarkSoft\GoRMG\Merchandising\Handle\PurchaseOrder;

use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Merchandising\Models\Fabric_composition;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\OrderItemDetail;
use SkylarkSoft\GoRMG\Merchandising\Models\PoWiseRecapReportTable;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Item;

class ColorSizeMatrixStore
{
    private $request;

    /**
     * ColorSizeMatrixStore constructor.
     * @param $request
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * @return bool
     */
    public function handle()
    {
        $color_size = [];
        $fabrication = Fabric_composition::find($this->request->composition_fabric_id)->first()->yarn_composition;

        try {
            DB::beginTransaction();
            PurchaseOrderDetail::where(['purchase_order_id' => $this->request->purchase_order_id, 'item_id' => $this->request->item_id])->delete();
            foreach ($this->request->size as $key => $sizes) {
                foreach ($sizes as $key2 => $quantity) {
                    $color_size[$key2]['purchase_order_id'] = $this->request->purchase_order_id;
                    $color_size[$key2]['item_id'] = $this->request->item_id;
                    $color_size[$key2]['color_id'] = $key;
                    $color_size[$key2]['size_id'] = $key2;
                    $color_size[$key2]['quantity'] = $quantity ?? 0;
                    $color_size[$key2]['gsm'] = $this->request->gsm;
                    $color_size[$key2]['color_type'] = $this->request->color_type;
                    $color_size[$key2]['country_id'] = $this->request->country_id;
                    $color_size[$key2]['fabric_description'] = $this->request->fabric_description;
                    $color_size[$key2]['fabrication'] = $fabrication;
                    $color_size[$key2]['composition_fabric_id'] = $this->request->composition_fabric_id;
                }
                PurchaseOrderDetail::insert($color_size);
                /* po wise recap report data insert */
            }
            $this->insert_po_master_data_in_recap($this->request->purchase_order_id);
            DB::commit();

            return true;
        } catch (\Exception $exception) {
            DB::rollBack();

            return false;
        }
    }

    private function insert_po_master_data_in_recap($purchase_order)
    {
        PoWiseRecapReportTable::where(['purchase_id' => $this->request->purchase_order_id, 'item_id' => $this->request->item_id])->delete();
        $purchase_order = PurchaseOrder::find($purchase_order);
        $order = Order::find($purchase_order->order_id);
        $order_details = OrderItemDetail::where(['order_id' => $order->id, 'item_id' => $this->request->item_id])->first();

        $recap = new PoWiseRecapReportTable();
        $recap->order_id = $purchase_order->order_id;
        $recap->purchase_id = $purchase_order->id;
        $recap->buyer = $purchase_order->buyer_id;
        $recap->booking_no = $order->booking_no;
        $recap->order_style_no = $order->order_style_no;
        $recap->po_no = $purchase_order->po_no;
        $recap->print = $purchase_order->print;
        $recap->emb = $purchase_order->embroidery;
        $recap->order_qty = $purchase_order->po_quantity;
        $recap->item_id = $this->request->item_id;
        $recap->unit_price = $order_details->unit_price;
        $recap->gsm = $order_details->gsm;
        $recap->fabrication = $order_details->fabrication;
        $recap->fac = Factory::find($order_details->factory_id)->factory_short_name;

        $recap->item = Item::find($order_details->item_id)->item_name;
        $quantity = PurchaseOrderDetail::where('purchase_order_id', $purchase_order->id)
            ->where('item_id', $order_details->item_id)
            ->sum('quantity');
        if ($order_details->item_category == 1) {
            $recap->t_shirt = $quantity;
        }
        if ($order_details->item_category == 2) {
            $recap->polo = $quantity;
        }
        if ($order_details->item_category == 3) {
            $recap->pant = $quantity;
        }
        if ($order_details->item_category == 4) {
            $recap->intimate = $quantity;
        }
        if ($order_details->item_category == 5) {
            $recap->others = $quantity;
        }
        $recap->total_value = ($quantity * $order_details->unit_price);
        $recap->shipment_date = $purchase_order->ex_factory_date;
        $recap->remarks = $purchase_order->remarks;
        $recap->save();
    }
}
