<?php

namespace SkylarkSoft\GoRMG\Planing\Models\ContainerProfile;

use App\Models\BelongsToFactory;
use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContainerProfile extends Model
{
    use SoftDeletes;
    use CommonModelTrait;
    use BelongsToFactory;

    protected $table = 'container_profiles';
    protected $primaryKey = 'id';
    protected $fillable = [
        'factory_id',
        'start_date',
        'end_date',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function details(): HasMany
    {
        return $this->hasMany(ContainerProfileDetail::class, 'container_profile_id');
    }
}
