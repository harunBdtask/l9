<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class ProductDepartments extends Model
{
    use SoftDeletes;

    protected $table = 'product_departments';

    protected $fillable = [
        'product_department',
        'status',
        'factory_id',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'is_deleted',
        'deleted_by',
    ];

    protected $dates = ['deleted_at'];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($post) {
            $post->factory_id = Auth::user()->factory_id;
            $post->created_by = Auth::id();
        });
    }

    public function factory(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id', 'id')->withDefault();
    }
}
