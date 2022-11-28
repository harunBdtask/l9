<?php

namespace SkylarkSoft\GoRMG\Inventory\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Knitting\Models\YarnRequisition;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use Illuminate\Database\Eloquent\Relations\HasMany;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;

class YarnIssue extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $table = 'yarn_issues';

    const ISSUE_BASIS = [
        1 => 'Independent',
        2 => 'Requisition'
    ];

    const KITING_SOURCE = [
        1 => 'In House',
        2 => 'Out-Bound Sub-Contract'
    ];

    const ISSUE_PURPOSE = [
        1 => 'Sales',
        2 => 'Loan',
        3 => 'Sample Material',
        4 => 'Yarn test',
        5 => 'Re-conning',
        6 => 'Damage',
        7 => 'Stolen',
        8 => 'Adjustment',
        9 => 'Lab Test',
        10 => 'Use Con Sales',
        11 => 'Moisturizing',
        12 => "Knitting",
    ];
    protected $fillable = [
        'issue_no',
        'supplier_id',
        'factory_id',
        'buyer_id',
        'issue_basis',
        'issue_purpose',
        'issue_date',
        'issue_to',
        'fabric_booking_no',
        'knitting_source',
        'challan_no',
        'location',
        'loan_party_id',
        'sample_type_id',
        'style_reference',
        'buyer_job_no',
        'service_booking',
        'ready_to_approve',
        'lock_no',
        'driver_name',
        'gate_pass_no',
        'vehicle_type',
        'vehicle_number',
        'remarks',
        'is_approved',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    public function getIssueBasisNameAttribute()
    {
        $issuBasisName = '';
        foreach (self::ISSUE_BASIS as $key => $value) {
            if ($key == $this->attributes['issue_basis']) {
                $issuBasisName = $value;
            }
        }
        return $issuBasisName;
    }

    public function getKnittingSourceNameAttribute()
    {
        $knittingSourceName = '';
        foreach (self::KITING_SOURCE as $key => $value) {
            if ($key == $this->attributes['knitting_source']) {
                $knittingSourceName = $value;
            }
        }
        return $knittingSourceName;
    }

    public function getIssuePurposeNameAttribute()
    {
        $issuePurposeName = '';
        foreach (self::ISSUE_PURPOSE as $key => $value) {
            if ($key == $this->attributes['issue_purpose']) {
                $issuePurposeName = $value;
            }
        }
        return $issuePurposeName;
    }

    protected static function boot()
    {
        parent::boot();
        static::created(function ($model) {
            $unique = date('Y') . '-' . str_pad($model->id, 6, '0', STR_PAD_LEFT);
            $model->issue_no = getPrefix() . 'YI-' . $unique;
            $model->gate_pass_no = $unique;
            $model->save();
        });
        static::saving(function ($model) {
            if(!$model->gate_pass_no){
                $unique = date('Y') . '-' . str_pad($model->id, 6, '0', STR_PAD_LEFT);
                $model->gate_pass_no = $unique;
                $model->save();
            }
        });
    }

    public function details(): HasMany
    {
        return $this->hasMany(YarnIssueDetail::class, 'yarn_issue_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id')->withDefault();
    }
    public function loanParty(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'loan_party_id')->withDefault();
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id')->withDefault();
    }

    public function issueBasisValue(): string
    {
        if (!array_key_exists($this->attributes['issue_basis'], self::ISSUE_BASIS)) {
            return '';
        }

        return self::ISSUE_BASIS[$this->attributes['issue_basis']];
    }

    public function knittingCompany()
    {
        if ($this->attributes['knitting_source'] == 1) {
            return Factory::query()->where('id', $this->attributes['issue_to'])->first(['id', 'factory_name as name']);
        }

        if ($this->attributes['knitting_source'] == 2) {
            return Supplier::query()->whereIn('party_type', ['Fabric Supplier', 'Supplier'])->first(['id', 'name']);
        }

        return collect([
            'id' => null,
            'name' => ''
        ]);
    }

    public function knittingSourceValue(): string
    {
        $knittingSourceValue = '';

        if ($this->attributes['knitting_source'] == 1) {
            $knittingSourceValue = 'In House';
        }

        if ($this->attributes['knitting_source'] == 2) {
            $knittingSourceValue = 'Out-Bound Sub-Contract';
        }

        return $knittingSourceValue;
    }

    public function scopeYarnCount(Builder $query, $countId): Builder
    {
        if (!$countId) {
            return $query;
        }
        return $query->whereHas('details', function ($q) use ($countId) {
            $q->where('yarn_count_id', $countId);
        });
    }

    public function scopeYarnComposition(Builder $query, $compositionId): Builder
    {
        if (!$compositionId) {
            return $query;
        }
        return $query->whereHas('details', function ($q) use ($compositionId) {
            $q->where('yarn_composition_id', $compositionId);
        });
    }

    public function scopeYarnType(Builder $query, $typeId): Builder
    {
        if (!$typeId) {
            return $query;
        }
        return $query->whereHas('details', function ($q) use ($typeId) {
            $q->where('yarn_type_id', $typeId);
        });
    }

    public function issueToSupplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'issue_to')->withDefault();
    }

    public function issueToFactory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'issue_to')->withDefault();
    }

    public function issueReturn(): HasMany
    {
        return $this->hasMany(YarnIssueReturn::class, 'issue_no', 'issue_no');
    }
}
