<?php

namespace SkylarkSoft\GoRMG\Procurement\Models;

use App\Traits\AuditAble;
use Illuminate\Http\Request;
use App\Models\BelongsToFactory;
use App\Models\CommonModelTrait;
use App\Models\BelongsToCreatedBy;
use App\Contracts\AuditAbleContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\BasicFinance\Models\Unit;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;
use SkylarkSoft\GoRMG\BasicFinance\Filters\Filter;
use SkylarkSoft\GoRMG\BasicFinance\Models\Project;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\SystemSettings\Models\Department;
use SkylarkSoft\GoRMG\Procurement\Services\ProcurementRequisitionService;

class ProcurementRequisition extends Model implements AuditAbleContract
{
    use SoftDeletes;
    use CommonModelTrait;
    use BelongsToFactory;
    use BelongsToCreatedBy;
    use AuditAble;

    const ITEM_TYPES = [
        'merchandising' => 'Merchandising',
        'general_store' => 'General Store',
        'dyes_store' => 'Dyes Store',
    ];
    const PRIORITY = [
        1 => 'Low',
        2 => 'Medium',
        3 => 'High',
    ];

    const STATUS = [
        0 => 'Created',
        1 => 'Approved',
        2 => 'Posted',
        3 => 'Cancelled',
    ];

    protected $table = 'procurement_requisitions';
    protected $primaryKey = 'id';
    protected $fillable = [
        'requisition_uid',
        'factory_id',
        'date',
        'required_date',
        'department_head',
        'priority',
        'status',
        'project_id',
        'department_id',
        'unit_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $appends = [
        'priority_value',
        'status_value',
    ];

    public static function booted()
    {
        static::saving(function ($model) {
            if (! $model->id && in_array('created_by', $model->getFillable())) {
                $model->requisition_uid = ProcurementRequisitionService::generateUniqueId();
            }
        });
    }

    public function getPriorityValueAttribute()
    {
        if (! empty($this->attributes['priority'])) {
            return self::PRIORITY[$this->attributes['priority']];
        }

        return null;
    }

    public function getStatusValueAttribute()
    {
        return self::STATUS[$this->attributes['status']];
    }

    public function scopeSearch(Builder $query, Request $request)
    {
        $query->when($request, function (Builder $query) use ($request) {
            $query->when(
                $request->input('search'),
                function ($q) use ($request) {
                    $q->where('requisition_uid', 'like', '%'.$request->get('search').'%');
                }
            )
            ->when(
                $request->get('start_date') && $request->get('end_date'),
                function ($q) use ($request) {
                    $q->whereBetween('date', [$request->get('start_date'), $request->get('end_date')]);
                    $q->orWhereBetween('required_date', [$request->get('start_date'), $request->get('end_date')]);
                }
            );
        });
    }

    public function procurementRequisitionDetails(): HasMany
    {
        return $this->hasMany(ProcurementRequisitionDetail::class, 'procurement_requisition_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id')->withDefault();
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id')->withDefault();
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id')->withDefault();
    }

    public function moduleName(): string
    {
        return 'procurement';
    }

    public function path(): string
    {
        return "/procurement/requisitions/create?id=$this->id";
    }

    public function approvalBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'department_head')->withDefault();
    }
}
