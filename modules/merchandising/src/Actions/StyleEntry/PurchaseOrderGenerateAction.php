<?php

namespace SkylarkSoft\GoRMG\Merchandising\Actions\StyleEntry;

use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PoColorSizeBreakdown;
use SkylarkSoft\GoRMG\Merchandising\Models\POFileModel;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;

class PurchaseOrderGenerateAction
{
    public function execute($order, $balance = 0)
    {
        $poList = POFileModel::query()->with('purchaseOrder')->where('style', $order->style_name)->get();
        foreach ($poList as $po) {
            if ($order->order_status_id == Order::PROJECTION && $balance < $po->po_quantity) {
                continue;
            }
            if (!PurchaseOrder::where('po_no', $po->po_no)->exists()) {
                $balance = $balance - $po->po_quantity;
            }

            $purchaseOrderAttributes = $this->poFormat($order, $po);
            $checkPurchaseOrder = PurchaseOrder::query()
                ->where('po_no', $purchaseOrderAttributes['po_no'])
                ->first();

            if (isset($checkPurchaseOrder)) {
                continue;
            }

            $purchaseOrder = new PurchaseOrder;
            $purchaseOrder->fill($purchaseOrderAttributes)->save();

            $poColorSizeBreakdownAttributes = $this->colorSizeBreakDownFormat($order, $po);
            $poColorSizeBreakdownAttributes['purchase_order_id'] = $purchaseOrder->id;
            $poColorSizeBreakdown = new  PoColorSizeBreakdown;
            $poColorSizeBreakdown->fill($poColorSizeBreakdownAttributes)->save();
        }
    }

    public function poFormat($order, $poFile): array
    {
        $quantityMatrix = collect($poFile->quantity_matrix)->first();
        return [
            'factory_id' => $order->factory_id,
            'order_status' => 'Confirm',
            'buyer_id' => $order->buyer_id,
            'order_id' => $order->id,
            'po_no' => $poFile->po_no,
            'avg_rate_pc_set' => $quantityMatrix['fob_price'] ?? null,
            'ready_to_approved' => 0,
            'is_approved' => 0,
            'un_approve_request' => null,
            'po_receive_date' => $quantityMatrix['po_received_date'] ?? date('Y-m-d'),
            'po_quantity' => $poFile->po_quantity,
            'po_pc_quantity' => 0,
            'ex_factory_date' => $quantityMatrix['x_factory_date'] ?? date('Y-m-d'),
            'lead_time' => 0,
            'country_id' => $quantityMatrix['country_id'] ?? null,
            'country_code' => $quantityMatrix['country_code'] ?? null,
            'season' => $quantityMatrix['season'] ?? null,
            'created_by' => auth()->user()->id,
        ];
    }

    public function colorSizeBreakDownFormat($order, $poFile): array
    {
        $item = collect($poFile->quantity_matrix)->pluck('item_id')->first();
        $colors = collect($poFile->quantity_matrix)->pluck('color_id')->unique()->values()->toArray();
        $sizes = collect($poFile->quantity_matrix)->pluck('size_id')->unique()->values()->toArray();

        $quantityMatrix = collect($poFile->quantity_matrix)->map(function ($matrix) {
            return [
                'size' => $matrix['size'],
                'color' => $matrix['color'],
                'value' => $matrix['value'],
                'size_id' => $matrix['size_id'],
                'color_id' => $matrix['color_id'],
                'particular' => $matrix['particulars']
            ];
        })->toArray();

        return [
            'factory_id' => $order->factory_id,
            'buyer_id' => $order->buyer_id,
            'order_id' => $order->id,
            'purchase_order_id' => null,
            'garments_item_id' => $item,
            'colors' => $colors,
            'sizes' => $sizes,
            'ratio_matrix' => [],
            'quantity_matrix' => $quantityMatrix,
            'quantity' => $poFile->po_quantity,
            'color_types' => null,
            'created_by' => auth()->user()->id,
        ];
    }
}
