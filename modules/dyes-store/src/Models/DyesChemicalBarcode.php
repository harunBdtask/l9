<?php

namespace SkylarkSoft\GoRMG\DyesStore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class DyesChemicalBarcode extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'dyes_chemicals_barcodes';

    protected $id = 'id';

    protected $fillable = [
        'code',
        'receive_date',
        'store_id',
        'item_id',
        'category_id',
        'brand_id',
        'uom_id',
        'dyes_chemicals_receive_id',
        'life_end_days',
        'lot_no',
        'batch_no',
        'mrr_no',
        'sr_no',
        'qty',
        'delivery_qty',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function scanned(): bool
    {
        return !$this->status;
    }

    public function voucher(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DyesChemicalsReceive::class, 'dyes_chemicals_receive_id')->withDefault();
    }

    public function item(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DsItem::class, 'item_id')->withDefault();
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DsInvItemCategory::class, 'category_id')->withDefault();
    }

    public function brand(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DsBrand::class, 'brand_id')->withDefault();
    }

    public function uom(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DsUom::class, 'uom_id')->withDefault();
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->created_by = Auth::user()->id;
        });

        static::updated(function ($model) {
            $model->updated_by = Auth::user()->id;
        });

        static::deleted(function ($model) {
            $model->deleted_by = Auth::user()->id;
        });
    }
}
