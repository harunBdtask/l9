<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Section extends Model
{
    use SoftDeletes;
    use FactoryIdTrait;

    protected $table = 'sections';
    protected $primary_key = 'id';
    protected $fillable = [
        'name',
        'description',
        'factory_id',
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
    }
}
