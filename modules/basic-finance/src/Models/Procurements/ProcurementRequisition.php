<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Models\Procurements;

use App\Contracts\AuditAbleContract;
use App\Models\BelongsToCreatedBy;
use App\Models\BelongsToFactory;
use App\Models\CommonModelTrait;
use App\Traits\AuditAble;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\BasicFinance\Filters\Filter;
use SkylarkSoft\GoRMG\BasicFinance\Models\Department;
use SkylarkSoft\GoRMG\BasicFinance\Models\Project;
use SkylarkSoft\GoRMG\BasicFinance\Models\Unit;
use SkylarkSoft\GoRMG\BasicFinance\Services\UId\ProcurementRequisitionService;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

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

    protected $table = 'procurement_requisitions';
    protected $primaryKey = 'id';
    protected $fillable = [
        'requisition_uid',
        'factory_id',
        'date',
        'project_id',
        'department_id',
        'unit_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public static function booted()
    {
        static::saving(function ($model) {
            if (!$model->id && in_array('created_by', $model->getFillable())) {
                $model->requisition_uid = ProcurementRequisitionService::generateUniqueId();
            }
        });
    }

    public function scopeSearch(Builder $query, Request $request)
    {
        $query->when($request, function (Builder $query) use ($request) {
            $query->when($request->input('factory_id'),
                Filter::applyFilter('factory_id', $request->input('factory_id')))
                ->when($request->input('date'),
                    Filter::applyFilter('date', $request->input('date')))
                ->when($request->input('project_id'),
                    Filter::applyFilter('project_id', $request->input('project_id')))
                ->when($request->input('department_id'),
                    Filter::applyFilter('department_id', $request->input('department_id')))
                ->when($request->input('unit_id'),
                    Filter::applyFilter('unit_id', $request->input('unit_id')));
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
        return 'basic-finance';
    }

    public function path(): string
    {
        return "/basic-finance/procurement/requisitions/create?id=$this->id";
    }
}
