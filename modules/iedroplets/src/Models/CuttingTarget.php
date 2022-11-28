<?php

namespace SkylarkSoft\GoRMG\Iedroplets\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\CuttingFloor;
use SkylarkSoft\GoRMG\SystemSettings\Models\CuttingTable;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItemGroup;

class CuttingTarget extends Model
{
    use FactoryIdTrait;
    use SoftDeletes;

    protected $fillable = [
        'cutting_floor_id',
        'cutting_table_id',
        'garments_item_group_id',
        'garments_item_id',
        'is_manual',
        'mp',
        'wh',
        'total_working_minutes',
        'smv',
        'req_efficiency',
        'adding',
        'sub',
        'npt',
        'target_date',
        'target',
        'hourly_target',
        'status',
        'factory_id',
        'remarks',
    ];

    protected $dates = ['deleted_at'];

    const TABLE_MODES = [
        0 => 'Manual',
        1 => 'Auto',
    ];

    const MANUAL_TABLE_MODE = 0;
    const AUTO_TABLE_MODE = 1;

    public function garmentsItemGroup(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(GarmentsItemGroup::class, 'garments_item_group_id')->withDefault();
    }

    public function garmentsItem(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(GarmentsItem::class, 'garments_item_id')->withDefault();
    }

    public function factory(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id')->withDefault();
    }

    public function floor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CuttingFloor::class, 'cutting_floor_id')->withDefault();
    }

    public function cuttingTable(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CuttingTable::class, 'cutting_table_id')->withDefault();
    }

    public static function dateFloorWiseTotalTarget($cutting_floor_id, $cutting_table_id, $target_date)
    {
        $query = self::query()
            ->selectRaw('SUM(target) as day_target')
            ->where('cutting_floor_id', $cutting_floor_id)
            ->where('cutting_table_id', $cutting_table_id)
            ->whereDate('target_date', $target_date)
            ->groupBy('cutting_floor_id')
            ->first();

        return $query ? $query->day_target : 0;
    }
}
