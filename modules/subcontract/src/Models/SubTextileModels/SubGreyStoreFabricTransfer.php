<?php

namespace SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels;

use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices\UId\MaterialFabricTransferService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class SubGreyStoreFabricTransfer extends Model
{
    use SoftDeletes;
    use CommonModelTrait;

    protected $table = "sub_grey_store_fabric_transfers";
    protected $primaryKey = "id";
    protected $fillable = [
        'transfer_uid',
        'criteria',
        'from_company',
        'to_company',
        'transfer_date',
        'transfer_type',
        'challan_no',
        'ready_to_approve',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function details(): HasMany
    {
        return $this->hasMany(SubGreyStoreFabricTransferDetail::class, 'fabric_transfer_id', 'id');
    }

    public static function booted()
    {
        static::saving(function ($model) {
            if (! $model->id && in_array('created_by', $model->getFillable())) {
                $model->transfer_uid = MaterialFabricTransferService::generateUniqueId();
            }
        });
    }

    public function fromCompany(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'from_company')->withDefault();
    }

    public function toCompany(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'to_company')->withDefault();
    }
}
