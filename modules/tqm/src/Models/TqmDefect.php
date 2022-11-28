<?php

namespace SkylarkSoft\GoRMG\TQM\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TqmDefect extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'factory_id',
        'section',
        'name',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $dates = ['deletes_at'];

    protected $appends = [
        'section_name'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (in_array('created_by', $model->getFillable())) {
                $model->created_by = userId();
            }
        });

        static::saving(function ($model) {
            if (!$model->id) {
                $model->created_by = userId();
            }
            $model->updated_by = userId();
        });

        static::deleting(function ($model) {
            if (in_array('deleted_by', $model->getFillable())) {
                DB::table($model->table)->where('id', $model->id)
                    ->update([
                        'deleted_by' => userId(),
                    ]);
            }
        });

        static::updating(function ($model) {
            if (in_array('updated_by', $model->getFillable())) {
                DB::table($model->table)->where('id', $model->id)->update([
                    'updated_by' => userId(),
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

    public static function getList($search = null)
    {
        return self::query()
            ->when($search, function ($query) use ($search) {
                $sectionKey = \array_search(strtoupper($search), TqmDefect::SECTIONS);
                if ($sectionKey) {
                    return $query->where('section', $sectionKey);
                }
                return $query->where('name', 'like', "%$search%");
            })
            ->orderBy('id', 'desc')
            ->paginate();
    }

    public function getSectionNameAttribute(): string
    {
        return isset($this->attributes['section']) ?
            array_key_exists($this->attributes['section'], self::SECTIONS) ? self::SECTIONS[$this->attributes['section']] : ''
            : '';
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id')->withDefault();
    }
}
