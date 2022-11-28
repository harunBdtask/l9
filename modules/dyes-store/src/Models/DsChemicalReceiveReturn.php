<?php

namespace SkylarkSoft\GoRMG\DyesStore\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\DyesStore\Services\DyesChemicalReceiveReturnService;
use SkylarkSoft\GoRMG\DyesStore\Traits\CommonBooted;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;

class DsChemicalReceiveReturn extends Model
{
    use SoftDeletes, CommonBooted;

    protected $table ="dyes_chemical_receive_return";
    protected $primaryKey = "id";
    protected $fillable = [
        'system_generate_id',
        'receive_id',
        'challan_no',
        'supplier_id',
        'return_date',
        'readonly',
        'details',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'details' => Json::class
    ];

    public static function booted()
    {
        static::saving(function ($model) {
            if (! $model->id && in_array('created_by', $model->getFillable())) {
                $model->system_generate_id = DyesChemicalReceiveReturnService::generateUniqueId();
            }
        });
    }

    /**
     * @return BelongsTo
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id')->withDefault();
    }

}
