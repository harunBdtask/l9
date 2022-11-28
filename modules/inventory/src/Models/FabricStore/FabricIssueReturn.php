<?php

namespace SkylarkSoft\GoRMG\Inventory\Models\FabricStore;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class FabricIssueReturn extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $table = 'fabric_issue_returns';

    protected $primaryKey = 'id';

    protected $fillable = [
        'issue_return_no',
        'factory_id',
        'return_date',
        'issue_no',
        'challan_no',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    const ABBR = 'FIR';

    public function details(): HasMany
    {
        return $this->hasMany(FabricIssueReturnDetail::class, 'issue_return_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->issue_return_no = getPrefix() . static::ABBR . '-' . date('y') . '-' . str_pad($model->id, 6, '0', STR_PAD_LEFT);
        });
    }
}
