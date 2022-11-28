<?php

namespace SkylarkSoft\GoRMG\Merchandising\Actions\Projection;

use Illuminate\Support\Str;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PoColorSizeBreakdown;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\Size;

class OrderProjectionAction
{
    public function attachProjectionPurchaseOrder($order)
    {
        if ($order->order_status_id != Order::PROJECTION) {
            return;
        }
        $buyer = Buyer::query()->where('id', $order->buyer_id)->first();
        $generatedPoNo = $buyer->name . '_' . $order->style_name . '_PO1';
        $poNo = Str::replace(' ', '_', $generatedPoNo);

        $purchaseOrderAttributes = [
            'factory_id' => $order->factory_id,
            'order_status' => Order::PO_PROJECTION,
            'buyer_id' => $order->buyer_id,
            'order_id' => $order->id,
            'po_no' => $poNo,
            'ready_to_approved' => 0,
            'po_receive_date' => date('Y-m-d'),
            'po_quantity' => $order->projection_qty,
            'po_pc_quantity' => 0,
            'avg_rate_pc_set' => 0.00,
            'status' => 'Active',
            'print_status' => 2,
            'embroidery_status' => 2,
            'country_id' => 241,
            'country_code' => 'USA',
            'matrix_type' => 1,
        ];
        $purchaseOrder = PurchaseOrder::query()->updateOrCreate([
            'po_no' => $poNo
        ], $purchaseOrderAttributes);

        $this->attachPoItemColorSizeBreakDown($order, $purchaseOrder);
    }

    public function attachPoItemColorSizeBreakDown($order, $purchaseOrder)
    {

        $defaultColor = Color::query()->first();
        $defaultSize = Size::query()->first();
        $poColorSizeBreakDown = collect($order->item_details['details'])
            ->pluck('item_id')
            ->map(function ($item) use ($order, $purchaseOrder, $defaultColor, $defaultSize) {
                return [
                    'factory_id' => $order->factory_id,
                    'buyer_id' => $order->buyer_id,
                    'order_id' => $order->id,
                    'purchase_order_id' => $purchaseOrder->id,
                    'garments_item_id' => (int)$item,
                    'colors' => json_encode($defaultColor->id),
                    'sizes' => json_encode($defaultSize->id),
                    'ratio_matrix' => null,
                    'quantity_matrix' => json_encode($this->quantityMatrix($order, $defaultSize, $defaultColor)),
                    'quantity' => $order->projection_qty,
                    'color_types' => null,
                ];
            })->toArray();

        PoColorSizeBreakdown::query()->insert($poColorSizeBreakDown);
    }

    private function quantityMatrix($order, $size, $color): array
    {
        $particulars = [
            'Item UPC/EAN',
            'Set Qty.',
            'Qty.',
            'Rate',
            'Ex. Cut %',
            'Plan Cut Qty.',
            'Short/Excess Qty',
            'Article No',
            'Short/Excess Value'
        ];

        return collect($particulars)->map(function ($particular) use ($order, $size, $color) {

            $matrix = [
                "size" => $size->name,
                "color" => $color->name,
                "size_id" => $size->id,
                "color_id" => $color->id,
                "particular" => $particular,
            ];
            $matrix['value'] = $particular === "Qty." ? $order->projection_qty : null;

            return $matrix;

        })->toArray();

    }

}
