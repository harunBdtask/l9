<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserRole extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'role_id','user_id',
    ];

    protected $dates = ['deleted_at'];

    public function role()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Role', 'role_id');
    }
}
