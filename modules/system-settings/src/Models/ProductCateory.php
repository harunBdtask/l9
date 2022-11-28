<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\Merchandising\QueryBuilders\CustomQuery;

class ProductCateory extends Model
{
    use FactoryIdTrait;
    use SoftDeletes;
    protected $table = "product_cateories";
    protected $fillable = [
        'category_name',
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
            $post->factory_id = Auth::user()->factory_id;
        });
        static::updating(function ($post) {
            $post->updated_by = Auth::user()->id;
        });
        static::deleting(function ($post) {
            $post->deleted_by = Auth::user()->id;
            $post->save();
        });
    }

    public function productCategoryWiseFactories(): HasMany
    {
        return $this->hasMany(ProductCategoryWiseFactory::class, 'product_category_id');
    }

    public function factory()
    {
        return $this->belongsTo(
            'SkylarkSoft\GoRMG\SystemSettings\Models\Factory',
            'factory_id',
            'id'
        );
    }

    public function preparedBy()
    {
        return $this->belongsTo(
            'SkylarkSoft\GoRMG\SystemSettings\Models\User',
            'created_by',
            'id'
        );
    }

    public function edittedBy()
    {
        return $this->belongsTo(
            'SkylarkSoft\GoRMG\SystemSettings\Models\User',
            'updated_by',
            'id'
        );
    }

    public function deletedByUser()
    {
        return $this->belongsTo(
            'SkylarkSoft\GoRMG\SystemSettings\Models\User',
            'deleted_by',
            'id'
        );
    }

    public function newEloquentBuilder($query): CustomQuery
    {
        return new CustomQuery($query);
    }
}
