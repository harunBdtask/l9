<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class SupplierFactoryMapping extends Model
{
    use SoftDeletes;
    protected $table = "suppliers_factory_mapping";
    protected $fillable = [
        'supplier_id',
        'tagged_factory_id',
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
            $post->created_by = Auth::user()->id;
        });
    }

    public function factory()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Factory', 'factory_id', 'id');
    }
}
