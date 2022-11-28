<?php

namespace SkylarkSoft\GoRMG\Inventory\Models\FabricStore;

use App\Casts\Json;
use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FabricTransferDetail extends Model
{
    use SoftDeletes, ModelCommonTrait;

    const ABBR = 'FTD';

    protected $table = 'fabric_transfer_details';

    protected $primaryKey = 'id';

    protected $fillable = [
        'unique_id',
        'transfer_id',
        'from_order', // Json
        'to_order', // Json
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'from_order' => Json::class,
        'to_order' => Json::class,
    ];

    public function transfer(): BelongsTo
    {
        return $this->belongsTo(FabricTransfer::class, 'transfer_id')->withDefault();
    }

    protected static function boot()
    {
        parent::boot();
        static::created(function ($model) {
            $model->unique_id = getPrefix() . static::ABBR . '-' . date('y') . '-' . str_pad($model->id, 6, '0', STR_PAD_LEFT);
            $model->save();
        });
    }
}
