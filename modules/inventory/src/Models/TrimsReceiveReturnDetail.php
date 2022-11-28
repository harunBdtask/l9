<?php


namespace SkylarkSoft\GoRMG\Inventory\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\ItemGroup;

class TrimsReceiveReturnDetail extends Model
{
    use SoftDeletes;

    protected $table = 'trims_receive_return_details';

    protected $fillable = [
        'trims_receive_return_id',
        'order_uniq_id',
        'ship_date',
        'style_name',
        'po_no',
        'brand_sup_ref',
        'item_id',
        'item_description',
        'gmts_sizes',
        'item_color',
        'item_size',
        'uom_id',
        'return_qty',
        'rate',
        'amount',
        'floor',
        'room',
        'rack',
        'shelf',
        'bin'
    ];

    public function itemGroup(): BelongsTo
    {
        return $this->belongsTo(ItemGroup::class,'item_id')->withDefault();
    }

    public function receiveReturn(): BelongsTo
    {
        return $this->belongsTo(TrimsReceiveReturn::class, 'trims_receive_return_id')->withDefault();
    }

    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = $this->rate * $this->return_qty;
    }
}
