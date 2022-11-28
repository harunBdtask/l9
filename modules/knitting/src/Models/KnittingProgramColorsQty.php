<?php

namespace SkylarkSoft\GoRMG\Knitting\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use SkylarkSoft\GoRMG\Inventory\Models\YarnReceiveDetail;
use SkylarkSoft\GoRMG\Inventory\YarnItemAction;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\Knitting\Models\YarnAllocationDetail;

class KnittingProgramColorsQty extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $table = 'knitting_program_color_qtys';

    protected $primaryKey = 'id';

    protected $fillable = [
        'plan_info_id',
        'knitting_program_id',
        'item_color_id',
        'item_color',
        'booking_qty',
        'program_qty',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
    protected $appends=[
        'knitting_yarns'
    ];

    protected $dates = ['deleted_at'];

    public function planningInfo(): BelongsTo
    {
        return $this->belongsTo(PlanningInfo::class, 'plan_info_id', 'id')->withDefault();
    }

    public function knittingProgram(): BelongsTo
    {
        return $this->belongsTo(KnittingProgram::class, 'knitting_program_id', 'id')->withDefault();
    }

    public function itemColor(): BelongsTo
    {
        return $this->belongsTo(Color::class, 'item_color_id', 'id')->withDefault();
    }
    public function getKnittingYarnsAttribute(): Collection
    {
        return YarnAllocationDetail::query()
            ->where('knitting_program_id', $this->attributes['knitting_program_id'])
            ->where('knitting_program_color_id', $this->attributes['id'])
            ->get()->map(function ($allocation) {
                $productCode = YarnReceiveDetail::query()
                    ->where(YarnItemAction::itemCriteria($allocation))
                    ->get()->pluck('product_code')->implode(', ');
                $allocation->product_code = $productCode;
                return $allocation;
            });
    }

    public function yarnAllocationDetail(): HasMany
    {
        return $this->hasMany(YarnAllocationDetail::class,'knitting_program_color_id','id');
    }
}
