<?php

namespace SkylarkSoft\GoRMG\Sample\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class SampleTrimsIssue extends Model
{
    use SoftDeletes;

    protected $table = 'sample_trims_issues';

    const ISSUE_BASIS = [
        [
            'id' => 1,
            'text' => 'Sample Order Basis',
        ],
        [
            'id' => 2,
            'text' => 'Independent',
        ],
        [
            'id' => 3,
            'text' => 'Issue Return',
        ],
        [
            'id' => 4,
            'text' => 'Order to Order Transfer',
        ],
    ];

    protected $fillable = [
        'unique_id',
        'issue_challan_no',
        'factory_id',
        'issue_basis_id',
        'delivery_to',
        'buyer_id',
        'style_name',
        'sample_id',
        'to_buyer_id',
        'to_style_name',
        'to_sample_id',
        'issue_date',
        'remarks',
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
            $model->unique_id = 'STS-' . date('y') . '-' . str_pad($model->id, 6, '0', STR_PAD_LEFT);
            $model->issue_challan_no = 'SIC' . date('y') . str_pad($model->id, 6, '0', STR_PAD_LEFT);
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

    public function trimsIssueDetails(): HasMany
    {
        return $this->hasMany(SampleTrimsIssueDetails::class, 'sti_id');
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class)->withDefault();
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class)->withDefault();
    }

    public function sample(): BelongsTo
    {
        return $this->belongsTo(SampleOrderRequisition::class)->withDefault();
    }
}
