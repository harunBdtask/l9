<?php

namespace SkylarkSoft\GoRMG\Finance\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use SkylarkSoft\GoRMG\Finance\Models\Unit;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Finance\Models\Project;
use SkylarkSoft\GoRMG\Finance\Models\AccountType;
use Illuminate\Database\Eloquent\Relations\HasOne;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int id
 * @property int factory_id
 * @property int unit_id
 * @property int bank_id
 * @property string date
 * @property string branch_name
 * @property string contract_person
 * @property string contract_number
 * @property string contract_email
 * @property string control_account_id
 * @property string account_number
 * @property string currency_type_id
 * @property string status
 * @property int created_by
 * @property int updated_by
 * @property int deleted_by
 */
class BankAccount extends Model
{
    use SoftDeletes, ModelCommonTrait;

    const STATUS = [
        1 => 'Active',
        0 => 'Inactive',
    ];

    protected $table = 'fi_bank_accounts';
    protected $primaryKey = 'id';
    protected $fillable = [
        'factory_id',
        'project_id',
        'unit_id',
        'bank_id',
        'date',
        'control_account_id',
        'ledger_account_id',
        'account_number',
        'account_type_id',
        'currency_type_id',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $casts = [
        'factory_id' => 'integer',
        'unit_id' => 'integer',
        'bank_id' => 'integer',
        'date' => 'date',
        'control_account_id' => 'integer',
        'account_number' => 'string',
        'currency_type_id' => 'integer',
        'status' => 'string',
    ];

    // public function scopeSearch(Builder $query, $search)
    // {
    //     $query->where('branch_name', 'LIKE', "%${search}%");
    // }
    public static $currencyType = [
        1 => 'Home',
        2 => 'Foreign'
    ];
    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id')->withDefault();
    }

    public function controlAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'control_account_id')->withDefault();
    }
    public function accountType(): BelongsTo
    {
        return $this->belongsTo(AccountType::class, 'account_type_id')->withDefault();
    }

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class, 'bank_id')->withDefault();
    }
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id')->withDefault();
    }
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id')->withDefault();
    }

    public function ledgerAccount(): HasOne
    {
        return $this->hasOne(Account::class, 'bank_account_id')->where('account_type', Account::SUB_LEDGER);
    }
    public function accountInfo(): BelongsTo
    {
        return $this->belongsTo(Account::class,'id', 'bank_account_id');
    }
}
