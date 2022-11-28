<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\FactoryIdTrait;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use FactoryIdTrait;
    use SoftDeletes;
    use CascadeSoftDeletes;

    protected $fillable = [
        'menu_name',
        'menu_url',
        'sort',
        'module_id',
        'submodule_id',
        'factory_id',
    ];

    protected $dates = ['deleted_at'];

    protected $cascadeDeletes = [
        'assignPermissions',
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function submodule()
    {
        return $this->belongsTo(Submodule::class);
    }

    public function sub_module()
    {
        return $this->belongsTo(Self::class, 'submodule_id', 'id')->withoutGlobalScope('factoryId')->withDefault();
    }

    public function assignPermissions()
    {
        return $this->hasMany(AssignPermission::class, 'menu_id');
    }

    /**
     * get module wise menus for dropdrown
     */
    public static function getMenus($module_id)
    {
        return self::withoutGlobalScope('factoryId')->where('module_id', $module_id)->pluck('menu_name', 'id')->all();
    }
}
