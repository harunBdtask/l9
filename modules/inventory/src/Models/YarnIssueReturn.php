<?php

namespace SkylarkSoft\GoRMG\Inventory\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;

class YarnIssueReturn extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $table = 'yarn_issue_returns';

    protected $fillable = [
        'issue_return_no',
        'factory_id',
        'issue_return_basis',
        'issue_no',
        'location',
        'return_source',
        'knitting_company_id',
        'return_date',
        'requisition_no',
        'return_challan',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

     protected static function boot()
     {
         parent::boot();
         static::created(function ($model) {
             $model->issue_return_no = getPrefix() . 'YIR-' . date('y') . '-' . str_pad($model->id, 6, '0', STR_PAD_LEFT);
             $model->save();
         });
     }

    public function details(): HasMany
    {
        return $this->hasMany(YarnIssueReturnDetail::class, 'yarn_issue_return_id');
    }

    public function scopeYarnCount(Builder $query, $countId): Builder
    {
        if (!$countId) {
            return $query;
        }
        return $query->whereHas('details', function($q) use ($countId) {
            $q->where('yarn_count_id', $countId);
        });
    }

    public function scopeYarnComposition(Builder $query, $compositionId): Builder
    {
        if (!$compositionId) {
            return $query;
        }
        return $query->whereHas('details', function($q) use ($compositionId) {
            $q->where('yarn_composition_id', $compositionId);
        });
    }

    public function scopeYarnType(Builder $query, $typeId): Builder
    {
        if (!$typeId) {
            return $query;
        }
        return $query->whereHas('details', function($q) use ($typeId) {
            $q->where('yarn_type_id', $typeId);
        });
    }
}
