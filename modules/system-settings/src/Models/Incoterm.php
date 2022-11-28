<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Incoterm extends Model
{
    use SoftDeletes;
    protected $table = "incoterms";

    protected $fillable = [
        'incoterm',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($post) {
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

    public function preparedBy()
    {
        return $this->belongsTo(
            User::class,
            'created_by',
            'id'
        );
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
