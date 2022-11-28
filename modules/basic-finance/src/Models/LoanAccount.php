<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class LoanAccount extends Model
{
    use HasFactory, ModelCommonTrait, SoftDeletes;

    protected $table = 'bf_loan_accounts';

    protected $fillable = [
        'factory_id',
        'project_id',
        'unit_id',
        'bank_id',
        'loan_type',
        'mode_of_loan',
        'control_account_id',
        'loan_account_number',
        'loan_creation_date',
        'expiry_date',
        'rate_of_interest',
        'loan_trioner',
        'grace_period',
        'number_of_instalments',
        'per_year_instalments',
        'size_of_instalments',
        'authorized_by',
        'authorize_date',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    protected $appends = ['type'];

    public static $types = [
        1 => 'Long Term',
        2 => 'Short Term',
    ];

    public static $modes = [];

    public function getTypeAttribute(): string
    {
        return self::$types[$this->attributes['loan_type']];
    }

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->created_by = Auth::id();
        });

        self::updating(function ($model) {
            $model->updated_by = Auth::id();
        });

        self::deleting(function ($model) {
            $model->deleted_by = Auth::id();
        });
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id')->withDefault();
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id')->withDefault();
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id')->withDefault();
    }

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class, 'bank_id')->withDefault();
    }

    public function controlAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'control_account_id')->withDefault();
    }

}

