<?php

namespace SkylarkSoft\GoRMG\GeneralStore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class GsInvBarcode extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'gs_inv_barcodes';

    protected $fillable = [
        'code',
        'item_id',
        'brand_id',
        'voucher_id',
        'type',
        'parent_id',
        'qty',
        'status',
        'company_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    public function scanned(): bool
    {
        return !$this->status;
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(GsBrand::class, 'brand_id', 'id')
            ->select('id', 'name')
            ->withDefault();
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(GsItem::class, 'item_id', 'id')
            ->select('id', 'name', 'uom')
            ->withDefault();
    }

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(GsInvVoucher::class, 'voucher_id', 'id')
            ->select('id', 'voucher_no')
            ->withDefault();
    }
}
