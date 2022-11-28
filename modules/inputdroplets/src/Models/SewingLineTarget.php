<?php

namespace SkylarkSoft\GoRMG\Inputdroplets\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\FactoryIdTrait;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Line;

class SewingLineTarget extends Model
{
    use FactoryIdTrait;
    use SoftDeletes;

    protected $table = 'sewing_line_targets';

    protected $fillable = [
        'floor_id',
        'line_id',
        'target_date',
        'operator',
        'helper',
        'smv',
        'efficiency',
        'target',
        'wh',
        'input_plan',
        'add_man_min',
        'sub_man_min',
        'mb',
        'npt',
        'shading_problem',
        'late_decision',
        'cutting_problem',
        'input_problem',
        'late_to_get_mc',
        'print_mistake',
        'late_to_recieve_print',
        'line_status',
        'remarks',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $dates = ['deleted_at'];

    public function line()
    {
        return $this->belongsTo(Line::class,'line_id');
    }

    public function factory()
    {
        return $this->belongsTo(Factory::class,'factory_id');
    }

    public static function sewingLineTargetDateWise($target_date, $line_id)
    {
        return self::where(['target_date' => $target_date, 'line_id' => $line_id])
            ->orderBy('id', 'asc')
            ->get();
    }

    public static function sewingFloorTargetDateWise($target_date, $floor_id)
    {
        return self::where(['target_date' => $target_date, 'floor_id' => $floor_id])
            ->orderBy('id', 'asc')
            ->get();
    }
}
