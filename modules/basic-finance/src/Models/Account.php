<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Models;

use App\ModelCommonTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\Merchandising\QueryBuilders\CustomQuery;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class Account extends Model
{
    use ModelCommonTrait;

    const BANK_ACCOUNT = 5;
    const CASH_IN_HAND_ACCOUNT = 6;
    const EXPORT_SALES = 150;
    const LOCAL_SALES = 151;
    const ACCOUNT_CODE_SEPARATOR = [
        '1' => '2',
        '2' => '2',
        '3' => '4',
        '4' => '4',
        '5' => '7',
        '6' => '7',
        '7' => '7',
        '8' => '10',
        '9' => '10',
        '10' => '10',
        '11' => '13',
        '12' => '13',
        '13' => '13',
    ];

    protected $table = 'bf_accounts';
    protected $fillable = [
        'name',
        'code',
        'particulars',
        'type_id',
        'parent_ac',
        'is_editable',
        'is_transactional',
        'is_active',
        'created_by',
        'updated_by',
        'factory_id'
    ];

    public static $types = [
        1 => 'Assets',
        2 => 'Equity',
        3 => 'Liabilities',
        4 => 'Income',
        5 => 'Expense',
    ];

    const ASSET = 1,
        EQUITY = 2,
        LIABILITY = 3,
        INCOME = 4,
        EXPENSE = 5;

    const EDITABLE = 1,
        NONEDITABLE = 0,
        TRANSACTIONAL = 1,
        NONTRANSACTIONAL = 0,
        ACTIVE = 1,
        INACTIVE = 0;

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if ($model->parent_ac) {
                // Make the parent account non-transactional
                DB::table('bf_accounts')
                    ->where('id', $model->parent_ac)
                    ->update([
                        'is_transactional' => self::NONTRANSACTIONAL
                    ]);
            }
            if ($model->id) {
                // Make the child accounts active/ inactive as per parent's active status
                DB::table('bf_accounts')
                    ->where('parent_ac', $model->id)
                    ->update([
                        'is_active' => $model->is_active
                    ]);
            }
        });
    }

    public function getTypeAttribute(): string
    {
        return self::$types[$this->attributes['type_id']];
    }

    public function parentAc(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'parent_ac');
    }

    public function childAcs(): HasMany
    {
        return $this->hasMany(self::class, 'parent_ac', 'id');
    }

    public function journalEntries(): HasMany
    {
        return $this->hasMany(Journal::class, 'account_id','id');
    }

    public function bank(): HasOne
    {
        return $this->hasOne(Bank::class, 'account_id');
    }

    public function openingLedgerBalance($date = null, $companyId, $projectId, $unitId, $departmentId=false, $costCenterId=false)
    {
        if ($date) {
            $debit = $this->journalEntries()
                ->where('trn_date', '<', $date)
                ->where('trn_type', 'dr')
                ->when($companyId, function ($query) use ($companyId) {
                    $query->whereHas('account', function ($query) use ($companyId) {
                        $query->where('factory_id', $companyId);
                    });
                })->when($projectId, function ($query) use ($projectId) {
                    $query->where('project_id', $projectId);
                })->when($unitId, function ($query) use ($unitId) {
                    $query->where('unit_id', $unitId);
                })->when($departmentId, function ($query) use ($departmentId) {
                    $query->where('department_id', $departmentId);
                })->when($costCenterId, function ($query) use ($costCenterId) {
                    $query->where('cost_center_id', $costCenterId);
                })->sum('trn_amount');

            $credit = $this->journalEntries()
                ->where('trn_date', '<', $date)
                ->where('trn_type', 'cr')
                ->when($companyId, function ($query) use ($companyId) {
                    $query->whereHas('account', function ($query) use ($companyId) {
                        $query->where('factory_id', $companyId);
                    });
                })->when($projectId, function ($query) use ($projectId) {
                    $query->where('project_id', $projectId);
                })->when($unitId, function ($query) use ($unitId) {
                    $query->where('unit_id', $unitId);
                })->when($departmentId, function ($query) use ($departmentId) {
                    $query->where('department_id', $departmentId);
                })->when($costCenterId, function ($query) use ($costCenterId) {
                    $query->where('cost_center_id', $costCenterId);
                })->sum('trn_amount');

            return $debit - $credit;
        }

        return 0;
    }

    public function openingLedgerFcBalance($date = null, $companyId, $projectId, $unitId, $departmentId, $costCenterId)
    {
        if ($date) {
            $debit = $this->journalEntries()
                ->where('trn_date', '<', $date)
                ->where('trn_type', 'dr')
                ->when($companyId, function ($query) use ($companyId) {
                    $query->whereHas('account', function ($query) use ($companyId) {
                        $query->where('factory_id', $companyId);
                    });
                })->when($projectId, function ($query) use ($projectId) {
                    $query->where('project_id', $projectId);
                })->when($unitId, function ($query) use ($unitId) {
                    $query->where('unit_id', $unitId);
                })->when($departmentId, function ($query) use ($departmentId) {
                    $query->where('department_id', $departmentId);
                })->when($costCenterId, function ($query) use ($costCenterId) {
                    $query->where('cost_center_id', $costCenterId);
                })->sum('fc');

            $credit = $this->journalEntries()
                ->where('trn_date', '<', $date)
                ->where('trn_type', 'cr')
                ->when($companyId, function ($query) use ($companyId) {
                    $query->whereHas('account', function ($query) use ($companyId) {
                        $query->where('factory_id', $companyId);
                    });
                })->when($projectId, function ($query) use ($projectId) {
                    $query->where('project_id', $projectId);
                })->when($unitId, function ($query) use ($unitId) {
                    $query->where('unit_id', $unitId);
                })->when($departmentId, function ($query) use ($departmentId) {
                    $query->where('department_id', $departmentId);
                })->when($costCenterId, function ($query) use ($costCenterId) {
                    $query->where('cost_center_id', $costCenterId);
                })->sum('fc');

            return $debit - $credit;
        }

        return 0;
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

    public function openingFcBalance($date = null)
    {
        if ($date) {
            $debit = $this->journalEntries()
                ->where('trn_date', '<', $date)
                ->where('trn_type', 'dr')
                ->sum('fc');

            $credit = $this->journalEntries()
                ->where('trn_date', '<', $date)
                ->where('trn_type', 'cr')
                ->sum('fc');

            return $debit - $credit;
        }

        return 0;
    }

    public function closingBalance($date = null)
    {
        if ($date) {
            $debit = $this->journalEntries()
                ->where('trn_date', '<=', $date)
                ->where('trn_type', 'dr')
                ->sum('trn_amount');

            $credit = $this->journalEntries()
                ->where('trn_date', '<=', $date)
                ->where('trn_type', 'cr')
                ->sum('trn_amount');

            return $debit - $credit;
        }

        return 0;
    }

    public function closingFcBalance($date = null)
    {
        if ($date) {
            $debit = $this->journalEntries()
                ->where('trn_date', '<=', $date)
                ->where('trn_type', 'dr')
                ->sum('fc');

            $credit = $this->journalEntries()
                ->where('trn_date', '<=', $date)
                ->where('trn_type', 'cr')
                ->sum('fc');

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
        return Account::query()->factoryFilter()->where('parent_ac', $this->id)->get();
    }

    public function explodeMonthYear($month): array
    {
        $explode = explode('-', $month);

        return [
            'year' => $explode[0],
            'month' => $explode[1],
        ];
    }

    /**
     * @param $query
     * @return CustomQuery
     */
    public function newEloquentBuilder($query): CustomQuery
    {
        return new CustomQuery($query);
    }

    public function accountInfo()
    {
        return $this->hasOne(AccountInfo::class, 'accounts_id', 'id')->withDefault();
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id')->withDefault();
    }

}
