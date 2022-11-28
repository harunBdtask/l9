<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;

class GarmentsItem extends Model
{
    use SoftDeletes;
    protected $table = 'garments_items';
    protected $guarded = [];

    public function productCategory()
    {
        return $this->belongsTo(ProductCateory::class, 'product_category_id')->withDefault();
    }

    public static function getGarmentsItemsByOrder($order_id)
    {
        $order = Order::query()->find($order_id);
        if ($order) {
            $item_details = $order->item_details && is_array($order->item_details) && array_key_exists('details', $order->item_details) ? $order->item_details['details'] : null;
            $garment_item_ids = $item_details && is_array($item_details) ? collect($item_details)->pluck('item_id')->toArray() : [];
            $garments_items = $garment_item_ids && count($garment_item_ids) ? self::query()->whereIn('id', $garment_item_ids)->pluck('name', 'id') : [];
        }
        return $garments_items ?? [];
    }
}
