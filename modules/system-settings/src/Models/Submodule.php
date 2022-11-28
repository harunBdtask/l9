<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Submodule extends Model
{
    use FactoryIdTrait;
    use SoftDeletes;

    protected $fillable = [
         'submodule_name',
         'module_id',
         'factory_id',
    ];

    protected $dates = ['deleted_at'];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public static function getSubmodules($module_id)
    {
        return self::where('module_id', $module_id)->pluck('submodule_name', 'id')->all();
    }
}
