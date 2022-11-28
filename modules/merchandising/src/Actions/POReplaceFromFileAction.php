<?php

namespace SkylarkSoft\GoRMG\Merchandising\Actions;

use Exception;
use SkylarkSoft\GoRMG\Merchandising\Actions\StyleEntry\PurchaseOrderGenerateAction;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PoColorSizeBreakdown;
use SkylarkSoft\GoRMG\Merchandising\Models\POFileLog;
use SkylarkSoft\GoRMG\Merchandising\Models\POFileModel;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;

class POReplaceFromFileAction
{
    /**
     * @throws Exception
     */
    public static function replace($POFileModel)
    {
        $po = PurchaseOrder::query()
            ->where('po_no', $POFileModel['po_no'])
            ->first();

        if (!$po) {
            $po = new PurchaseOrder();
        }

        $poColorSizeBreakdown = PoColorSizeBreakdown::query()
            ->where('purchase_order_id', $po['id'])
            ->first();

        if (!$poColorSizeBreakdown) {
            $poColorSizeBreakdown = new PoColorSizeBreakdown();
        }

        $order = Order::query()->where('style_name', $POFileModel['style'])->first();
        $purchaseOrderGenerateAction = new PurchaseOrderGenerateAction();

        $poData = $purchaseOrderGenerateAction->poFormat($order, $POFileModel);
        $po->fill($poData)->save();

        $poColorSizeData = $purchaseOrderGenerateAction->colorSizeBreakDownFormat($order, $POFileModel);
        $poColorSizeBreakdown->fill($poColorSizeData)->save();
    }
}
