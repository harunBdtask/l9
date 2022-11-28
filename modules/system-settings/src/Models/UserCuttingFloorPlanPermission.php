<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Model;

class UserCuttingFloorPlanPermission extends Model
{
    protected $table = 'user_cutting_floor_plan_permissions';

    protected $fillable = [
        'cutting_floor_id',
        'user_id',
        'is_locked',
    ];

    public function cuttingFloor()
    {
        return $this->belongsTo(CuttingFloor::class, 'cutting_floor_id')->withDefault();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }
}
