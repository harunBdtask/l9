<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class YarnComposition extends Model
{
    use SoftDeletes;
    protected $table = 'yarn_compositions';
    protected $guarded = [];
    protected $dates = ['deleted_at'];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($post) {
            $post->factory_id = Auth::user()->factory_id;
            $post->created_by = Auth::id();
        });
        static::updating(function ($post) {
            $post->updated_by = Auth::id();
        });
        static::deleting(function ($post) {
            $post->deleted_by = Auth::id();
            $post->save();
        });
    }

    public function factory()
    {
        return $this->belongsTo(Factory::class, 'factory_id', 'id')->withDefault();
    }

    public function preparedBy()
    {
        return $this->belongsTo(
            User::class,
            'created_by',
            'id'
        )->withDefault();
    }

    public function edittedBy()
    {
        return $this->belongsTo(
            User::class,
            'updated_by',
            'id'
        );
    }

    public function deletedByUser()
    {
        return $this->belongsTo(
            User::class,
            'deleted_by',
            'id'
        );
    }
}
