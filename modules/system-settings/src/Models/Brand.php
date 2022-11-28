<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Brand extends Model
{
    use SoftDeletes;

    protected $table = 'brands';

    protected $fillable = [
        'brand_name',
        'brand_type',
        'factory_id',
    ];

    protected $dates = ['deleted_at'];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($post) {
            $post->factory_id = Auth::user()->factory_id;
        });
    }

    public function factory()
    {
        return $this->belongsTo(Factory::class, 'factory_id', 'id');
    }

    public function knitCards()
    {
        return $this->hasMany('Skylarksoft\Knittingdroplets\Models\KnitCard', 'brand_id', 'id');
    }

    public function yarnIssueHistories()
    {
        return $this->hasMany('Skylarksoft\Inventorydroplets\Models\YarnIssueHistory', 'brand_id', 'id');
    }

    public function yarnChallans()
    {
        return $this->hasMany('Skylarksoft\Inventorydroplets\Models\YarnChallan', 'brand_id', 'id');
    }

    public function yarnStockSummaries()
    {
        return $this->hasMany('Skylarksoft\Inventorydroplets\Models\YarnStockSummary', 'brand_id', 'id');
    }

    public function yarnPurchaseRequisitionDetails()
    {
        return $this->hasMany('Skylarksoft\Inventorydroplets\Models\YarnPurchaseRequisitionDetail', 'brand_id', 'id');
    }
}
