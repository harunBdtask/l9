<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stores extends Model
{
    use SoftDeletes;

    protected $table = 'stores';

    protected $fillable = [
        'name',
        'factory_id',
        'location',
        'item_category_id',
        'status',
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
            $post->created_by = userId();
        });
        static::updating(function ($post) {
            $post->updated_by = userId();
        });
        static::deleted(function ($post) {
            $post->deleted_by = userId();
        });
    }

    public function factory()
    {
        return $this->belongsTo(Factory::class, 'factory_id', 'id')->withDefault();
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_category_id', 'id')->withDefault();
    }

}
