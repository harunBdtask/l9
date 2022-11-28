<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FabricType extends Model
{
    use SoftDeletes;

    protected $table = 'fabric_types';

    protected $fillable = [
        'fabric_type_name',
        'factory_id',
    ];
    protected $dates = ['deleted_at'];

    protected $cascadeDeletes = [
        'knittingAllocationDetails',
        'knitCards',
        'rolls',
    ];

    public static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            $model->factory_id = factoryId();
        });
    }

    public function getFabricTypeNameAttribute()
    {
        $fabric_type = isset($this->attributes['fabric_type_name']) ? strtoupper($this->attributes['fabric_type_name']) : "";

        return "{$fabric_type}";
    }

    public function setFabricTypeNameAttribute($value)
    {
        $this->attributes['fabric_type_name'] = strtoupper($value);
    }

    public function budgetFabricBooking()
    {
        return $this->hasMany('Skylarksoft\Merchandising\Models\BudgetFabricBooking', 'fabric_type_id', 'id');
    }

    public function budgetYarnComponents()
    {
        return $this->hasMany('Skylarksoft\Merchandising\Models\BudgetYarnComponent', 'yarn_part_fabric_type_id', 'id');
    }

    public function budgetKnittingComponents()
    {
        return $this->hasMany('Skylarksoft\Merchandising\Models\BudgetKnittingComponent', 'knitting_part_fabric_type_id', 'id');
    }

    public function budgetDyeingComponents()
    {
        return $this->hasMany('Skylarksoft\Merchandising\Models\BudgetDyeingComponent', 'dyeing_part_fabric_type_id', 'id');
    }

    public function finishFabStores()
    {
        return $this->hasMany('Skylarksoft\Textiledroplets\Models\FinishFabStore', 'fabric_type', 'id');
    }

    public function knittingAllocationDetails()
    {
        return $this->hasMany('Skylarksoft\Knittingdroplets\Models\KnittingAllocationDetail', 'fabric_type_id', 'id');
    }

    public function knitCards()
    {
        return $this->hasMany('Skylarksoft\Knittingdroplets\Models\KnitCard', 'fabric_type_id', 'id');
    }

    public function rolls()
    {
        return $this->hasMany('Skylarksoft\Knittingdroplets\Models\Roll', 'fabric_type_id', 'id');
    }

    public function factory()
    {
        return $this->belongsTo(Factory::class, 'factory_id', 'id');
    }
}
