<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class ProductCategoryWiseFactory extends Model
{
    use SoftDeletes;

    protected $table = 'product_category_wise_factories';
    protected $primaryKey = 'id';
    protected $fillable = [
        'product_category_id',
        'factory_id'
    ];

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id')->withDefault();
    }

    public function productCategory(): BelongsTo
    {
        return $this->belongsTo(ProductCateory::class, 'product_category_id')->withDefault();
    }

    protected static function booted()
    {
        self::creating(function ($model) {
            $model->created_by = Auth::id();
        });

        self::updating(function ($model) {
            $model->updated_by = Auth::id();
        });

        self::deleting(function ($model) {
            $model->deleted_by = Auth::id();
        });
    }
}
