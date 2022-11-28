<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class BankAccount extends Model
{
    use SoftDeletes, ModelCommonTrait;

    const STATUS = [
        1 => 'Active',
        0 => 'Inactive',
    ];

    const CURRENCY_TYPES = [
        1 => 'Home',
        2 => 'Foren',
    ];

    protected $table = 'bf_bank_accounts';
    protected $primaryKey = 'id';
    protected $fillable = [
        'factory_id',
        'project_id',
        'unit_id',
        'account_id',
        'bank_id',
        'date',
        'branch_name',
        'account_type_id',
        'contract_person',
        'contract_number',
        'contract_email',
        'account_number',
        'currency_type_id',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

//    protected $casts = [
//        'factory_id' => 'integer',
//        'bank_id' => 'integer',
//        'date' => 'date',
//        'branch_name' => 'string',
//        'contract_person' => 'string',
//        'contract_number' => 'string',
//        'contract_email' => 'string',
//        'account_number' => 'string',
//        'currency_type_id' => 'integer',
//        'status' => 'string',
//    ];

    public function scopeSearch(Builder $query, $search)
    {
        $query->when($search, function (Builder $builder) use ($search) {
            $builder->where('branch_name', 'LIKE', "%${search}%");
        })->when($search, function (Builder $builder) use ($search) {
            $builder->orWhere('account_number', 'LIKE', "%${search}%");
        })->when($search, function (Builder $builder) use ($search) {
            $builder->orWhereHas('bank', function ($query) use ($search) {
                $query->whereHas('account', function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%${search}%");
                });
            });
        });
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id')->withDefault();
    }

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class, 'bank_id')->withDefault();
    }

//
//    public function unit(): BelongsTo
//    {
//        return $this->belongsTo(AcUnit::class, 'unit_id')->withDefault();
//    }
//
//    public function ledgerAccount(): HasOne
//    {
//        return $this->hasOne(Account::class, 'bank_account_id')->where('account_type', Account::SUB_LEDGER);
//    }
//
    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id')->withDefault();
    }
//
//    public function controlAccount(): BelongsTo
//    {
//        return $this->belongsTo(Account::class, 'control_account_id')->withDefault();
//    }
}
