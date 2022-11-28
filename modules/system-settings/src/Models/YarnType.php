<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class YarnType extends Model
{
    use SoftDeletes;
    use FactoryIdTrait;


    protected $table = 'yarn_types';
    protected $fillable = [
        'yarn_type',
        'factory_id',
    ];
    protected $dates = ['deleted_at'];

    public static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            $model->factory_id = factoryId();
        });
    }

    public function factory()
    {
        return $this->belongsTo(Factory::class, 'factory_id', 'id')->withDefault();
    }

    public function knitCards()
    {
        return $this->hasMany('Skylarksoft\Knittingdroplets\Models\KnitCard', 'yarn_type_id', 'id');
    }

    public function knitYarnRequisitionDetails()
    {
        return $this->hasMany('Skylarksoft\Knittingdroplets\Models\KnitYarnRequisitionDetails', 'yarn_type_id', 'id');
    }

    public function yarnIssueHistories()
    {
        return $this->hasMany('Skylarksoft\Inventorydroplets\Models\YarnIssueHistory', 'yarn_type_id', 'id');
    }

    public function yarnChallans()
    {
        return $this->hasMany('Skylarksoft\Inventorydroplets\Models\YarnChallan', 'yarn_type_id', 'id');
    }

    public function yarnStockSummaries()
    {
        return $this->hasMany('Skylarksoft\Inventorydroplets\Models\YarnStockSummary', 'yarn_type_id', 'id');
    }

    public function yarnPurchaseRequisitionDetails()
    {
        return $this->hasMany('Skylarksoft\Inventorydroplets\Models\YarnPurchaseRequisitionDetail', 'yarn_type_id', 'id');
    }
}
