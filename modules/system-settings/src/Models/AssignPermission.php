<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssignPermission extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'module_id',
        'menu_id',
        'permissions',
        'factory_id',
    ];

    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }

    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id')->withoutGlobalScope('factoryId')->withDefault();
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id')->withoutGlobalScope('factoryId')->withDefault();
    }

    public function factory()
    {
        return $this->belongsTo(Factory::class, 'factory_id')->withDefault();
    }
}
