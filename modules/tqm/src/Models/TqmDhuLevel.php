<?php

namespace SkylarkSoft\GoRMG\TQM\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class TqmDhuLevel extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'factory_id',
        'section',
        'level',
        'comparison_status',
        'color',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $dates = ['deletes_at'];

    protected $appends = [
        'section_name',
        'comparison_status_value'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = Auth::id();
        });

        static::updating(function ($model) {
            $model->updated_by = Auth::id();
        });

        static::deleting(function ($model) {
            if (in_array('deleted_by', $model->getFillable())) {
                DB::table($model->table)->where('id', $model->id)
                    ->update([
                        'deleted_by' => userId(),
                    ]);
            }
        });
    }

    const SECTIONS = [
        '1' => 'CUTTING',
        '2' => 'SEWING',
        '3' => 'FINISHING'
    ];

    const CUTTING_SECTION = 1;
    const SEWING_SECTION = 2;
    const FINISHING_SECTION = 3;

    const COMPARISON_STATUSES = [
        '1' => '>',
        '2' => '=',
        '3' => '<'
    ];

    public static function getList($search = null)
    {
        return self::query()
            ->when($search, function ($query) use ($search) {
                $sectionKey = \array_search(strtoupper($search), TqmDhuLevel::SECTIONS);
                if ($sectionKey) {
                    return $query->where('section', $sectionKey);
                }
                $comparisonKey = \array_search(strtoupper($search), TqmDhuLevel::COMPARISON_STATUSES);
                if ($comparisonKey) {
                    return $query->where('comparison_status', $comparisonKey);
                }
                return $query->where('level', 'like', "%$search%");
            })
            ->orderBy('id', 'desc')
            ->paginate();
    }

    public function getSectionNameAttribute(): string
    {
        return array_key_exists($this->attributes['section'], self::SECTIONS) ? self::SECTIONS[$this->attributes['section']] : '';
    }

    public function getComparisonStatusValueAttribute(): string
    {
        return array_key_exists($this->attributes['comparison_status'], self::COMPARISON_STATUSES) ? self::COMPARISON_STATUSES[$this->attributes['comparison_status']] : '';
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id')->withDefault();
    }
}
