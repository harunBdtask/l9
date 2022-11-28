<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;

class Currency extends Model
{
    use SoftDeletes;
    protected $table = "currencies";
    protected $fillable = [
        'currency_name',
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
            $post->created_by = Auth::user()->id;
        });
        static::updating(function ($post) {
            $post->updated_by = Auth::user()->id;
        });
        static::deleting(function ($post) {
            $post->deleted_by = Auth::user()->id;
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
