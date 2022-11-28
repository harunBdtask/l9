<?php

namespace SkylarkSoft\GoRMG\Knitting\Models;

use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Inventory\Models\YarnIssueDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\KnittingFloor;

class YarnRequisition extends Model
{
    use SoftDeletes, CommonModelTrait;

    protected $fillable = [
        'requisition_no',
        'knitting_floor_id',
        'program_id',
        'unallocated_qty',
        'attention',
        'req_date',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public static function booted()
    {
        static::created(function ($model) {
            $generate = str_pad($model->id, 5, "0", STR_PAD_LEFT);
            $model->requisition_no = getPrefix() . 'YRI-' . date('y') . '-' . $generate;
            $model->save();
        });
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(KnittingProgram::class, 'program_id')->withDefault();
    }

    public function details(): HasMany
    {
        return $this->hasMany(YarnRequisitionDetail::class, 'yarn_requisition_id');
    }

    public function knittingFloor(): BelongsTo
    {
        return $this->belongsTo(KnittingFloor::class)->withDefault();
    }

    public function yarnIssue(): HasMany
    {
        return $this->hasMany(YarnIssueDetail::class, 'demand_no', 'requisition_no');
    }
}
