<?php

namespace SkylarkSoft\GoRMG\Sample\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SampleTrimsIssueDetails extends Model
{
    use SoftDeletes;

    protected $table = 'sample_trims_issue_details';

    protected $fillable = [
        'sti_id',
        'item_group_id',
        'supplier_id',
        'size_id',
        'item_group_uom_id',
        'details',
        'calculations',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'details' => Json::class,
        'calculations' => Json::class,
    ];

    protected static function boot()
    {
        parent::boot();

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
}
