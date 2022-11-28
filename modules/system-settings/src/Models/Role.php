<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name','slug','factory_id',
    ];

    protected $dates = ['deleted_at'];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }
}
