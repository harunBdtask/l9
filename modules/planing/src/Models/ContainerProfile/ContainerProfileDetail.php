<?php

namespace SkylarkSoft\GoRMG\Planing\Models\ContainerProfile;

use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContainerProfileDetail extends Model
{
    use SoftDeletes;
    use CommonModelTrait;

    protected $table = 'container_profile_details';
    protected $primaryKey = 'id';
    protected $fillable = [
        'container_profile_id',
        'container_no',
        'cbm',
        'ex_factory_date',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function containerProfile(): BelongsTo
    {
        return $this->belongsTo(ContainerProfile::class, 'container_profile_id')->withDefault();
    }
}
