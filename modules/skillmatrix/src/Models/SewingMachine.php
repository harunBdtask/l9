<?php

namespace SkylarkSoft\GoRMG\Skillmatrix\Models;

use App\FactoryIdTrait;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SewingMachine extends Model
{
    use SoftDeletes;
    use FactoryIdTrait;
    use CascadeSoftDeletes;

    protected $table = 'sewing_machines';
    protected $fillable = [
        'name',
        'created_by',
        'updated_by',
        'deleted_by',
        'factory_id',
    ];

    protected $dates = ['deleted_at'];
    protected $cascadeDeletes = ['sewingOperatorSkills'];

    public function sewingOperatorSkills(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SewingOperatorSkill::class, 'sewing_machine_id', 'id');
    }

    public static function sewingMachines(): array
    {
        return self::pluck('name', 'id')->prepend('Select a machine', '')->all();
    }
}
