<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Module extends Model
{
    use FactoryIdTrait;
    use SoftDeletes;

    protected $fillable = ['module_name', 'sort', 'factory_id'];

    protected $dates = ['deleted_at'];

    protected $cascadeDeletes = [
        'menus',
        'assignPermissions',
    ];

    public function menus(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Menu::class, 'module_id');
    }

    public function assignPermissions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AssignPermission::class, 'module_id');
    }
}
