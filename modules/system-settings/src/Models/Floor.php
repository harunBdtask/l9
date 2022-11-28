<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\FactoryIdTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use SkylarkSoft\GoRMG\Inputdroplets\Models\SewingLineTarget;
use SkylarkSoft\GoRMG\Planing\Models\FactoryCapacity;

class Floor extends Model
{
    use FactoryIdTrait;
    use SoftDeletes;

    protected $table = 'floors';

    protected $fillable = [
        'factory_id',
        'floor_no',
        'sort',
    ];

    protected $dates = ['deleted_at'];

    public static $sewingOutputDate; // Formatted as 'Y-m-d';

    public function factory()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Factory', 'factory_id');
    }

    public function lines()
    {
        return $this->hasMany(Line::class, 'floor_id');
    }

    public function linesWithoutGlobalScope()
    {
        return $this->hasMany(Line::class, 'floor_id')->withoutGlobalScopes();
    }

    public function hourlySewingProductionReport()
    {
        return $this->hasMany('SkylarkSoft\GoRMG\Sewingdroplets\Models\HourlySewingProductionReport', 'floor_id');
    }

    public function hourlyTodaySewingProductionReport()
    {
        return $this->hasMany('SkylarkSoft\GoRMG\Sewingdroplets\Models\HourlySewingProductionReport', 'floor_id')
            ->where('production_date', date('Y-m-d'));
    }

    public function sewingOutputsByDate()
    {
        return $this->hasMany('SkylarkSoft\GoRMG\Sewingdroplets\Models\HourlySewingProductionReport', 'floor_id')
            ->where('production_date', self::$sewingOutputDate);
    }

    public function sewingTargetsByDate()
    {
        return $this->hasMany(SewingLineTarget::class, 'floor_id')
            ->where('target_date', self::$sewingOutputDate)
            ->orderBy('id', 'asc');
    }

    public function sewingSummaryForLastProductiveDay()
    {
        $lastDate = Carbon::yesterday();
        $cacheKeyForSewingSummary = 'sewing_summary_for_floor_' . $this->id . 'at_date_' . $lastDate->toDateString();

        if (Cache::has($cacheKeyForSewingSummary)) {
            return Cache::get($cacheKeyForSewingSummary);
        }

        self::$sewingOutputDate = $lastDate->toDateString();
        $sewingOutputs = $this->sewingOutputsByDate()->get();

        $attempt = 0;
        while ($sewingOutputs->isEmpty() && $attempt < 15) {
            $lastDate = $lastDate->subDay();
            self::$sewingOutputDate = $lastDate->toDateString();
            $sewingOutputs = $this->sewingOutputsByDate()->get();
            $attempt++;
        }

        $targetedOutput = 0;
        foreach ($this->sewingTargetsByDate as $sewingTarget) {
            $targetedOutput += $sewingTarget->wh * $sewingTarget->target;
        }

        $sewingSummary = [
            'output' => $sewingOutputs->sum('total_output'),
            'target' => $targetedOutput,
        ];

        Cache::put($cacheKeyForSewingSummary, $sewingSummary, 1440);

        return $sewingSummary;
    }

    public function sewingOutputForLastProductiveDay($previous_day)
    {
        $lastDate = Carbon::parse($previous_day);
        $cacheKeyForSewingSummary = 'sewing_summary_for_floor_' . $this->id . 'at_date_' . $lastDate->toDateString();

        if (Cache::has($cacheKeyForSewingSummary)) {
            return Cache::get($cacheKeyForSewingSummary);
        }

        self::$sewingOutputDate = $lastDate->toDateString();
        $sewingOutputs = $this->sewingOutputsByDate()->get();

        $attempt = 0;
        while ($sewingOutputs->isEmpty() && $attempt < 15) {
            $lastDate = $lastDate->subDay();
            self::$sewingOutputDate = $lastDate->toDateString();
            $sewingOutputs = $this->sewingOutputsByDate()->get();
            $attempt++;
        }

        $sewingSummary = [
            'output' => $sewingOutputs->sum('total_output'),
        ];

        Cache::put($cacheKeyForSewingSummary, $sewingSummary, 1440);

        return $sewingSummary;
    }

    public static function floors()
    {
        return self::pluck('floor_no', 'id')->all();
    }

    public function capacities(): HasMany
    {
        return $this->hasMany(FactoryCapacity::class, 'floor_id', 'id');
    }
}
