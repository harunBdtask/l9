<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\ColorType;
use SkylarkSoft\GoRMG\SystemSettings\Models\Item;
use SkylarkSoft\GoRMG\SystemSettings\Models\Size;

class PurchaseOrderDetail extends Model
{
    use SoftDeletes;

    protected $table = 'purchase_order_details';
    protected $fillable = [
        'factory_id', 'buyer_id', 'order_id', 'purchase_order_id', 'garments_item_id',
        'color_id', 'size_id', 'rate', 'excess_cut_percent', 'quantity', 'article_no',
        'created_by', 'updated_by', 'deleted_by',
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($post) {
            $post->created_by = userId();
        });

        static::deleted(function ($post) {
            $post->deleted_by = userId();
        });
    }

    public function buyer()
    {
        return $this->belongsTo(Buyer::class, 'buyer_id', 'id')->withDefault();
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id', 'id')->withDefault();
    }

    public function get_order_no()
    {
        return $this->belongsTo(Order::class, 'order_style_id', 'id')->withDefault();
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id')->withDefault();
    }

    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id', 'id')->withDefault();
    }

    public function size()
    {
        return $this->belongsTo(Size::class, 'size_id', 'id')->withDefault();
    }

    public function countries()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id')->withDefault();
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id')->withDefault();
    }

    // parameter $validaion for bundlecard generation form validation repopulate
    public static function getColors($purchaseOrderIds, $validation = false, $order_id = null, $garments_item_id = null)
    {
        $purchaseOrderId = ($purchaseOrderIds == 'all') ? null : $purchaseOrderIds;
        if (!is_array($purchaseOrderIds)) {
            $purchaseOrderIds = (array) $purchaseOrderIds;
        }

        $colors = self::with('colors:id,name')
            ->join('colors', 'purchase_order_details.color_id', 'colors.id')
            ->when($purchaseOrderId, function($query) use($purchaseOrderIds) {
                $query->whereIn('purchase_order_details.purchase_order_id', $purchaseOrderIds);
            })
            ->when($order_id, function($query) use($order_id){
                $query->where('order_id', $order_id);
            })
            ->when($garments_item_id, function($query) use($garments_item_id){
                $query->where('garments_item_id', $garments_item_id);
            });

            if ($validation) {
                $colors = $colors->where('quantity', '>', 0);
            }
        $colors = $colors->pluck('colors.name', 'colors.id')->all();

        return $colors;
    }

    public static function getSizes($purchaseOrderIds, $garmentsItemId = null)
    {
        $sizes = self::with('size:id,name')
            ->whereIn('purchase_order_id', $purchaseOrderIds)
            ->when($garmentsItemId, function ($query) use ($garmentsItemId) {
                $query->where('garments_item_id', $garmentsItemId);
            })
            ->get()
            ->map(function ($item) {
                return $item->size;
            })
            ->unique('id')
            ->values()
            ->all();

        return $sizes;
    }

    public static function getColorsByOrder($orderId)
    {
        return self::withoutGlobalScope('factoryId')
            ->leftJoin('purchase_orders', 'purchase_orders.id', 'purchase_order_details.purchase_order_id')
            ->join('colors', 'purchase_order_details.color_id', 'colors.id')
            ->leftJoin('orders', 'orders.id', 'purchase_orders.order_id')
            ->where('orders.id', $orderId)
            ->pluck('colors.name', 'colors.id')
            ->all();
    }

    public static function getItemWiseOrderQuantity($order_id, $garments_item_id)
    {
        return self::where([
            'order_id' => $order_id,
            'garments_item_id' => $garments_item_id,
        ])->sum('quantity');
    }

    public static function getItemWisePoQuantity($purchase_order_id, $garments_item_id)
    {
        return self::where([
            'purchase_order_id' => $purchase_order_id,
            'garments_item_id' => $garments_item_id,
        ])->sum('quantity');
    }

    public static function getColorWisePoQuantity($purchase_order_id, $color_id)
    {
        return self::where([
            'purchase_order_id' => $purchase_order_id,
            'color_id' => $color_id,
        ])->sum('quantity');
    }

    public static function getColorWiseOrderQuantity($order_id, $color_id)
    {
        return self::where([
            'order_id' => $order_id,
            'color_id' => $color_id,
        ])->sum('quantity');
    }

    public function fabricComposition()
    {
        return $this->belongsTo(Fabric_composition::class, 'composition_fabric_id')->withDefault();
    }

    public function color_types()
    {
        return $this->belongsTo(ColorType::class, 'color_type')->withDefault();
    }

    public function po()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id', 'id')->withDefault();
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id')->withDefault();
    }
}
