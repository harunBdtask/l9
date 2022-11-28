<?php


namespace SkylarkSoft\GoRMG\Merchandising\Actions\StyleEntry;

use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PriceQuotation;
use SkylarkSoft\GoRMG\Merchandising\Transform\PriceQuotation\TransformInterface;
use Throwable;

class StyleGenerationAction
{
    /**
     * @param PriceQuotation $priceQuotation
     * @param TransformInterface $priceQuotationTransform
     * @return void
     * @throws Throwable
     */
    public function forOrder(PriceQuotation $priceQuotation, TransformInterface $priceQuotationTransform)
    {
        $orderAttributes = $priceQuotationTransform->transform($priceQuotation);
        DB::beginTransaction();
        $order = Order::query()->updateOrCreate([
            'order_copy_from' => $orderAttributes['order_copy_from']
        ], $orderAttributes);
        (new PurchaseOrderGenerateAction())->execute($order);
        DB::commit();
    }
}
