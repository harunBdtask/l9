<?php

namespace SkylarkSoft\GoRMG\Inventory\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use Illuminate\Database\Eloquent\Relations\HasMany;

class YarnTransfer extends Model
{
    use SoftDeletes, ModelCommonTrait;
    protected $table = 'yarn_transfers';

    const TRANSFER_CRITERIA = [
        'store_to_store'     => 'Store To Store',
        'company_to_company' => 'Company To Company'
    ];

    protected $fillable = [
        'transfer_no',
        'transfer_criteria',
        'factory_id',
        'to_factory_id',
        'from_store_id',
        'to_store_id',
        'transfer_date',
        'challan_no',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $modelId = str_pad($model->id, 5, "0", STR_PAD_LEFT);
            $model->transfer_no = getPrefix() . 'YT-' . date('y') . '-' . $modelId;
            $model->save();
        });

    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id')->withDefault();
    }


    public function fromStore(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'from_store_id')->withDefault();
    }

    public function toStore(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'to_store_id')->withDefault();
    }

    public function details(): HasMany
    {
        return $this->hasMany(YarnTransferDetail::class, 'yarn_transfer_id');
    }

}
