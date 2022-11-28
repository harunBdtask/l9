<?php

namespace SkylarkSoft\GoRMG\Sample\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class SampleTrimsReceive extends Model
{
    use SoftDeletes;

    protected $table = 'sample_trims_receives';

    protected $fillable = [
        'unique_id',
        'trims_issue_id',
        'trims_issue_unique_id',
        'issue_challan_no',
        'factory_id',
        'receive_date',
        'total_calculation',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'total_calculation' => Json::class,
    ];

    protected static function boot()
    {
        parent::boot();

        self::created(function ($model) {
            $model->unique_id = 'STR-' . date('y') . '-' . str_pad($model->id, 6, '0', STR_PAD_LEFT);
            $model->save();
        });

        self::creating(function ($model) {
            $model->created_by = Auth::id();
        });

        self::updating(function ($model) {
            $model->updated_by = Auth::id();
        });

        self::deleted(function ($model) {
            if (in_array('deleted_by', $model->getFillable())) {
                DB::table($model->table)->where('id', $model->id)->update([
                    'deleted_by' => Auth::id(),
                ]);
            }
        });
    }

    public function trimsReceiveDetails(): HasMany
    {
        return $this->hasMany(SampleTrimsReceiveDetails::class, 'str_id');
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class)->withDefault();
    }
}
