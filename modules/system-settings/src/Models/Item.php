<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Item extends Model
{
    use SoftDeletes;

    protected $table = 'items';

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($post) {
            $post->created_by = Auth::id();
        });
        static::updating(function ($post) {
            $post->updated_by = Auth::id();
        });
    }

    public function item_group_assign()
    {
        return $this->hasMany(ItemGroupAssign::class, 'item_id', 'id');
    }

    public function prepared_by()
    {
        return $this->belongsTo(
            User::class,
            'created_by',
            'id'
        );
    }

    public function uom_data(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            UnitOfMeasurement::class,
            'uom_id',
            'id'
        );
    }

    public function editted_by()
    {
        return $this->belongsTo(
            User::class,
            'updated_by',
            'id'
        );
    }

    public function deleted_by_user()
    {
        return $this->belongsTo(
            User::class,
            'deleted_by',
            'id'
        );
    }
}
