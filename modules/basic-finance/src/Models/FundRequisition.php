<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FundRequisition extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $table = 'bf_fund_requisitions';
    protected $primaryKey = 'id';
    protected $fillable = [
        'project_id',
        'requisition_no',
        'requisition_date',
        'name',
        'designation',
        'expect_receive_date',
        'is_approved',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function scopeSearch($query, $search)
    {
        $query->where('requisition_no', 'LIKE', "%${search}%");
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id')->withDefault();
    }

    public function details(): HasMany
    {
        return $this->hasMany(FundRequisitionDetail::class, 'requisition_id');
    }

    public function auditApproved(): HasMany
    {
        return $this->hasMany(FundRequisitionAuditApproval::class, 'requisition_id');
    }

    public function acApproved(): HasMany
    {
        return $this->hasMany(FundRequisitionAccountApproval::class, 'requisition_id');
    }
}
