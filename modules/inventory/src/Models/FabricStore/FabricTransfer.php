<?php

namespace SkylarkSoft\GoRMG\Inventory\Models\FabricStore;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class FabricTransfer extends Model
{
    use SoftDeletes, ModelCommonTrait;

    const ABBR = 'FT';

    protected $table = 'fabric_transfers';

    protected $primaryKey = 'id';

    protected $fillable = [
        'transfer_no',
        'transfer_criteria',
        'transfer_date',
        'factory_id',
        'location',
        'to_factory_id',
        'to_location',
        'challan_no',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function details(): HasMany
    {
        return $this->hasMany(FabricTransferDetail::class, 'transfer_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::created(function ($model) {
            $model->transfer_no = getPrefix() . static::ABBR . '-' . date('y') . '-' . str_pad($model->id, 6, '0', STR_PAD_LEFT);
            $model->save();
        });
    }
}
