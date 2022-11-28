<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\FactoryIdTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Inputdroplets\Models\CuttingInventoryChallan;
use SkylarkSoft\GoRMG\Inputdroplets\Models\SewingLineTarget;

class Line extends Model
{
    use FactoryIdTrait;
    use SoftDeletes;

    protected $table = 'lines';

    protected $fillable = [
        'line_no',
        'floor_id',
        'sort',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $dates = ['deleted_at'];
    public static $targetDate; //format is 'Y-m-d';

    public function factory()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Factory', 'factory_id');
    }

    public function floorWithoutGlobalScopes()
    {
        return $this->belongsTo(Floor::class, 'floor_id')->withoutGlobalScopes();
    }

    public function floor()
    {
        return $this->belongsTo(Floor::class, 'floor_id');
    }

    public function sewingLineTarget()
    {
        return $this->hasMany(SewingLineTarget::class);
    }

    public function sewingTargetsByDateWithoutGlobalScopes()
    {
        return $this->hasMany(SewingLineTarget::class)
            ->withoutGlobalScopes()
            ->where('target_date', self::$targetDate)
            ->orderBy('id', 'asc');
    }

    public function sewingLineTodayTarget()
    {
        return $this->hasMany(SewingLineTarget::class)
            ->where('target_date', date('Y-m-d'))
            ->orderBy('id', 'asc');
    }

    public function sewingTargetsByDate()
    {
        return $this->hasMany(SewingLineTarget::class)
            ->where('target_date', self::$targetDate)
            ->orderBy('id', 'asc');
    }

    public function cuttingInventoryChallan()
    {
        return $this->hasMany(CuttingInventoryChallan::class, 'line_id');
    }

    public function sewingoutput()
    {
        return $this->hasMany('Skylarksoft\Sewingdroplets\Models\Sewingoutput', 'line_id');
    }

    public function todaysOutput()
    {
        return $this->hasMany('Skylarksoft\Sewingdroplets\Models\Sewingoutput', 'line_id')
            ->where('created_at', '>=', Carbon::now()->startOfDay())
            ->where('created_at', '<=', Carbon::now()->endOfDay());
    }

    public function yesterdayOutput()
    {
        return $this->hasMany('Skylarksoft\Sewingdroplets\Models\Sewingoutput', 'line_id')->whereDate('created_at', date('Y-m-d', strtotime("-1 days")));
    }

    public function yesterdaySewingLineTarget()
    {
        return $this->hasOne(SewingLineTarget::class)->whereDate('target_date', date('Y-m-d', strtotime("-1 days")));
    }

    public function hourlyProductionReport()
    {
        return $this->hasMany('Skylarksoft\Sewingdroplets\Models\HourlySewingProductionReport', 'line_id');
    }

    public function todayHourlyProductionReport()
    {
        return $this->hasMany('Skylarksoft\Sewingdroplets\Models\HourlySewingProductionReport', 'line_id')
            ->where('production_date', date('Y-m-d'));
    }

    public function yesterdayHourlyProductionReport()
    {
        return $this->hasMany('Skylarksoft\Sewingdroplets\Models\HourlySewingProductionReport', 'line_id')
            ->where('production_date', Carbon::yesterday()->format('Y-m-d'));
    }

    public function inspectionSchedule()
    {
        return $this->hasOne('Skylarksoft\Iedroplets\Models\NextSchedule');
    }
}
