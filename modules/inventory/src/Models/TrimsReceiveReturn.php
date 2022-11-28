<?php

namespace SkylarkSoft\GoRMG\Inventory\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;

class TrimsReceiveReturn extends Model
{
    use HasFactory;

    protected $table = "trims_receive_returns";
    protected $fillable = [
        'factory_id',
        'return_date',
        'returned_source',
        'returned_to',
        'store_id',
        'gate_pass_no',
        'buyer_id',
        'year',
        'unique_id',
        'style_name',
        'po_no',
        'po_quantity',
        'order_uom',
        'order_uom_id',
        'shipment_date',
        'return_basis'
    ];

    const RETURNED_SOURCES = [
        'in_house'  => 'In House',
        'out_bound' => 'Out-Bound'
    ];

    public function details(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TrimsReceiveReturnDetail::class, 'trims_receive_return_id');
    }

    protected $casts = [
        'po_no' => Json::class,
    ];

    public function factory(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id')->withDefault();
    }

    public function returnToSupplier(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'returned_to')->withDefault();
    }

    public function returnToFactory(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Factory::class, 'returned_to')->withDefault();
    }

    public function store(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id')->withDefault();
    }

}
