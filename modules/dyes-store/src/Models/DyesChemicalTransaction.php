<?php

namespace SkylarkSoft\GoRMG\DyesStore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\DyesStore\Traits\CommonBooted;
use SkylarkSoft\GoRMG\SystemSettings\Models\Stores;

class DyesChemicalTransaction extends Model
{
    use HasFactory, SoftDeletes, CommonBooted;

    /**
     * @var string
     */
    protected $table = 'dyes_chemical_transactions';

    /**
     * @var string
     */
    protected $primary_key = 'id';

    /**
     * @var string[]
     */
    protected $fillable = [
        'item_id',
        'category_id',
        'brand_id',
        'qty',
        'rate',
        'trn_date',
        'trn_type',
        'ref',
        'sub_store_id',
        'trn_store',
        'dyes_chemical_receive_id',
        'receive_id',
        'created_by',
        'updated_by',
        'deleted_by',
        'uom_id',
        'sr_no',
        'lot_no',
        'mrr_no',
        'batch_no',
        'life_end_days',
        'dyes_chemical_issue_id',
        'dyes_chemical_receive_return_id',
        'dyes_chemical_issue_return_id',
        'generate_barcodes',
        'barcode_id',
    ];

    /**
     * @return BelongsTo
     */
    public function dyesChemicalsReceive(): BelongsTo
    {
        return $this->belongsTo(DyesChemicalsReceive::class, 'dyes_chemical_receive_id', 'id')->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(DsInvItemCategory::class, 'category_id')->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(DsBrand::class, 'brand_id')->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(DsItem::class, 'item_id')->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function uom(): BelongsTo
    {
        return $this->belongsTo(DsUom::class, 'uom_id')->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function fromStore(): BelongsTo
    {
        return $this->belongsTo(Stores::class, 'sub_store_id')->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function toStore(): BelongsTo
    {
        return $this->belongsTo(Stores::class, 'trn_store')->withDefault();
    }

    /**
     * Boot Function
     */
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
