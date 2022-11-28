<?php

namespace SkylarkSoft\GoRMG\Skillmatrix\Models;

use App\FactoryIdTrait;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SewingProcess extends Model
{
    use SoftDeletes;
    use FactoryIdTrait;
    use CascadeSoftDeletes;

    protected $table = 'sewing_processes';
    protected $fillable = [
        'name',
        'standard_capacity',
        'created_by',
        'updated_by',
        'deleted_by',
        'factory_id',
    ];

    protected $dates = ['deleted_at'];
    protected $cascadeDeletes = ['sewingOperatorSkills'];

    public function sewingOperatorSkills(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SewingOperatorSkill::class, 'sewing_process_id', 'id');
    }

    public static function processes(): array
    {
        return self::pluck('name', 'id')->prepend('Select a process', '')->all();
    }
}
