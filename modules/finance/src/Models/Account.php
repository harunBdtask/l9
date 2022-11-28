<?php

namespace SkylarkSoft\GoRMG\Finance\Models;

use App\Traits\BelongsToUser;
use App\Traits\Booted;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Account extends Model
{
    use Booted, BelongsToUser, SoftDeletes;

    protected $table = 'fi_accounts';

    protected $fillable = [
        'name',
        'code',
        'particulars',
        'type_id',
        'account_type',
        'parent_ac',
        'bank_account_id',
        'status',
        'is_transactional',
        'created_by',
        'updated_by'
    ];
    const BANK_ACCOUNT = 5;
    const PARENT = 1, GROUP = 2, CONTROL = 3, LEDGER = 4, SUB_LEDGER = 5;

    public static $types = [
        1 => 'Assets',
        2 => 'Liabilities',
        3 => 'Equity',
        4 => 'Revenues',
        5 => 'Expenses',
    ];

    const ASSET = 1, LIABILITY = 2, EQUITY = 3, REVENUE_OP = 4, REVENUE_NOP = 5, EXPENSE_OP = 6, EXPENSE_NOP = 7;

    const STATUS = [
        1 => 'Active',
        0 => 'In-Active',
    ];

    public function getTypeAttribute()
    {
        return self::$types[$this->attributes['type_id']];
    }

    public function parentAc()
    {
        return $this->belongsTo(Account::class, 'parent_ac');
    }

    public function journalEntries()
    {
        return $this->hasMany(Journal::class, 'account_id');
    }

    public function openingBalance($date = null)
    {
        if ($date) {
            $debit = $this->journalEntries()
                ->where('trn_date', '<', $date)
                ->where('trn_type', 'dr')
                ->sum('trn_amount');

            $credit = $this->journalEntries()
                ->where('trn_date', '<', $date)
                ->where('trn_type', 'cr')
                ->sum('trn_amount');

            return $debit - $credit;
        }

        return 0;
    }

    public function getCodeAttribute()
    {
        $code = $this->attributes['code'];
        $parent = $this->parentAc;

        while (!$code && $parent) {
            $code = $parent->code;
            $parent = $parent->parentAc;
        }

        return $code;
    }

    public function children()
    {
        return Account::where('parent_ac', $this->id)->get();
    }

    public function scopeParentAccounts(Builder $query, $type)
    {
        return $query->where([
            'account_type' => self::PARENT,
            'type_id' => $type
        ]);
    }

    public function scopeGroupAccounts(Builder $query)
    {
        return $query->where([
            'account_type' => self::GROUP,
        ]);
    }

    public function scopeControlAccounts(Builder $query)
    {
        return $query->where([
            'account_type' => self::CONTROL,
        ]);
    }

    public function scopeLedgerAccounts(Builder $query)
    {
        return $query->where([
            'account_type' => self::LEDGER,
        ]);
    }

    public function scopeSubLedgerAccounts(Builder $query)
    {
        return $query->where([
            'account_type' => self::SUB_LEDGER,
        ]);
    }

    public function scopeSearch(Builder $query, Request $request)
    {
        $key = trim($request->get('key'));
        $value = trim($request->get('value'));

        return $query->when($key == 'account_code', function (Builder $query) use ($value) {
            $query->where('code', 'Like', '%' . $value . '%');
        })->when($key == 'parent_account', function (Builder $query) use ($value) {
            $query->whereHas('accountInfo', function (Builder $query) use ($value) {
                $query->whereHas('parentAccount', function (Builder $query) use ($value) {
                    $query->where('name', 'Like', '%' . $value . '%');
                });
            });
        })->when($key == 'group_account', function (Builder $query) use ($value) {
            $query->whereHas('accountInfo', function (Builder $query) use ($value) {
                $query->whereHas('groupAccount', function (Builder $query) use ($value) {
                    $query->where('name', 'Like', '%' . $value . '%');
                });
            });
        })->when($key == 'control_account', function (Builder $query) use ($value) {
            $query->whereHas('accountInfo', function (Builder $query) use ($value) {
                $query->whereHas('controlAccount', function (Builder $query) use ($value) {
                    $query->where('name', 'Like', '%' . $value . '%');
                });
            })->orWhere('name', 'Like', '%' . $value . '%');
        })->when($key == 'ledger_account', function (Builder $query) use ($value) {
            $query->where('name', 'Like', '%' . $value . '%');
        })
            ->when($key == 'account_type', function (Builder $query) use ($value) {

            $typeId = collect(self::$types)->map(function ($type, $key) use ($value) {
                return $type == $value ? $key : '';
            })->filter(function ($value) {
                return $value != '';
            })->first();

            $query->where('type_id', 'Like', '%' . $typeId . '%');
        });
    }

    public function accountInfo()
    {
        return $this->hasOne(AccountInfo::class, 'accounts_id', 'id')->withDefault();
    }

    public function transactions($startDate, $endDate, $unitId = null, $costCenterId = null): array
    {
        $transaction = [
            'opening_debit' => 0,
            'opening_credit' => 0,
            'transaction_debit' => 0,
            'transaction_credit' => 0,
            'balance_debit' => 0,
            'balance_credit' => 0,
        ];

        $openingTransactions = $this->journalEntries()->where('trn_date', '<', $startDate)
            ->when($unitId, function ($query) use ($unitId) {
                $query->where('unit_id', $unitId);
            })->when($costCenterId, function ($query) use ($costCenterId) {
                $query->where('cost_center_id', $costCenterId);
            })->get();

        $transaction['opening_debit'] = collect($openingTransactions)->where('trn_type', 'dr')
            ->sum('trn_amount');
        $transaction['opening_credit'] = collect($openingTransactions)->where('trn_type', 'cr')
            ->sum('trn_amount');

        $currentTransactions = $this->journalEntries()->whereBetween('trn_date', [$startDate, $endDate])
            ->when($unitId, function ($query) use ($unitId) {
                $query->where('unit_id', $unitId);
            })->when($costCenterId, function ($query) use ($costCenterId) {
                $query->where('cost_center_id', $costCenterId);
            })->get();

        $transaction['transaction_debit'] = collect($currentTransactions)->where('trn_type', 'dr')
            ->sum('trn_amount');
        $transaction['transaction_credit'] = collect($currentTransactions)->where('trn_type', 'cr')
            ->sum('trn_amount');

        $transaction['balance_debit'] = $transaction['opening_debit'] + $transaction['transaction_debit'];
        $transaction['balance_credit'] = $transaction['opening_credit'] + $transaction['transaction_credit'];

        return $transaction;
    }

    public function hasLedger()
    {
        return $this->with('accountInfo.controlAccount')
            ->where('account_type', Account::LEDGER)
            ->get();
    }

    public function getLedgerAccountsByControlId($id)
    {
        $accounts = AccountInfo::query()
            ->where('control_account_id',$id)
            ->with('controlLedgerAccount')
            ->get();
        $ledger_accounts = [];
        if($accounts){
                $ledger_accounts = collect($accounts)->map(function($item){
                    return [
                        'id' => $item->controlLedgerAccount->id,
                        'text' => $item->controlLedgerAccount->name
                    ];
            });
        }
        return $ledger_accounts;
    }
}
