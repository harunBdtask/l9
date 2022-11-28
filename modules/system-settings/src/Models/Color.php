<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\FactoryIdTrait;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail;

class Color extends Model
{
    use SoftDeletes;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = Auth::id();
            $model->factory_id = Auth::user()->factory_id;
        });

        static::updating(function ($model) {
            $model->updated_by = Auth::id();
        });
    }

    protected $table = 'colors';

    protected $fillable = [
        'name',
        'factory_id',
        'status',
        'parent_id',
        'tag',
        'style',
    ];

    protected $dates = ['deleted_at'];

    const COLOR_TYPE = [
        'garments_color' => 1,
        'team' => 2,
        'fabric_color' => 3,
        'stripe_color' => 4,
    ];

    protected $cascadeDeletes = ['knittingAllocationDetailsColors'];


    public function color_details(): HasMany
    {
        return $this->hasMany(PurchaseOrderDetail::class)->withoutGlobalScope('factoryId');
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id', 'id')->withDefault();
    }

    public function knittingAllocationDetailsColors(): HasMany
    {
        return $this->hasMany('Skylarksoft\Knittingdroplets\Models\KnittingAllocationDetailsColor', 'color_id', 'id');
    }

    public function scopePull($query, $type)
    {
        return $query->where("status", self::COLOR_TYPE[$type]);
    }

    public function garmentsColor(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'parent_id', 'id');
    }

    public function budgets()
    {
//        return $this->hasMany(Budget::);
    }
}
