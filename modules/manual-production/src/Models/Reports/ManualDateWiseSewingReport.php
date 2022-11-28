<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Models\Reports;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Floor;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;
use SkylarkSoft\GoRMG\SystemSettings\Models\Line;
use SkylarkSoft\GoRMG\SystemSettings\Models\Size;

class ManualDateWiseSewingReport extends Model
{

    protected $table = 'manual_date_wise_sewing_reports';

    protected $fillable = [
        'production_date',
        'factory_id',
        'subcontract_factory_id',
        'buyer_id',
        'order_id',
        'garments_item_id',
        'purchase_order_id',
        'color_id',
        'size_id',
        'floor_id',
        'line_id',
        'sub_sewing_floor_id',
        'sub_sewing_line_id',
        'input_qty',
        'sewing_output_qty',
        'sewing_rejection_qty'
    ];

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class)->withDefault();
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class)->withDefault();
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class)->withDefault();
    }

    public function floor(): BelongsTo
    {
        return $this->belongsTo(Floor::class)->withDefault();
    }

    public function size(): BelongsTo
    {
        return $this->belongsTo(Size::class)->withDefault();
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class)->withDefault();
    }

    public function line(): BelongsTo
    {
        return $this->belongsTo(Line::class)->withDefault();
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(GarmentsItem::class, 'garments_item_id')->withDefault();
    }

    public function getOrderColorFloorWiseFirstInputDate($order_id, $color_id, $floor_id): string
    {
        return self::query()
                ->where([
                    'floor_id' => $floor_id,
                    'order_id' => $order_id,
                    'color_id' => $color_id,
                ])->where('input_qty', '>', 0)
                ->orderBy('production_date', 'asc')
                ->first()
                ->production_date ?? '';
    }

    public function getOrderColorFloorWiseLastInputDate($order_id, $color_id, $floor_id): string
    {
        return self::query()
                ->where([
                    'floor_id' => $floor_id,
                    'order_id' => $order_id,
                    'color_id' => $color_id,
                ])->where('input_qty', '>', 0)
                ->orderBy('production_date', 'desc')
                ->first()
                ->production_date ?? '';
    }

    public static function getLineOrderItemColorWiseTotalInputQty($line_id, $order_id, $garments_item_id, $color_id)
    {
        return self::query()
            ->where([
                'line_id' => $line_id,
                'order_id' => $order_id,
                'garments_item_id' => $garments_item_id,
                'color_id' => $color_id,
            ])
            ->sum('input_qty');
    }

    public static function getLineOrderItemColorWiseTotalOutputQty($line_id, $order_id, $garments_item_id, $color_id)
    {
        return self::query()
            ->where([
                'line_id' => $line_id,
                'order_id' => $order_id,
                'garments_item_id' => $garments_item_id,
                'color_id' => $color_id,
            ])
            ->sum('sewing_output_qty');
    }
}
