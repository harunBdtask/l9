<?php

namespace SkylarkSoft\GoRMG\Finance\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Finance\Models\Account;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bank extends Model
{
    use SoftDeletes, ModelCommonTrait;

    const HOME = 1, FOREN = 2;

    const CURRENCY_TYPES = [
        1 => 'Home',
        2 => 'Foreign',
    ];

    protected $table = 'fi_banks';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'short_name',
        'currency_type_id',
        'account_id',
        'branch_name',
        'branch_address',
        'swift_code',
        'description',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    // protected $casts = [
    //     'name' => 'string',
    //     'currency_type' => 'integer',
    //     'details' => 'string',
    // ];

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id')->withDefault();
    }

    public function scopeSearch(Builder $query, $search)
    {
        $query->where('short_name', 'LIKE', "%${search}%")
            ->when($search, function (Builder $builder) use ($search) {
            $builder->orWhereHas('account', function ($query) use ($search) {
                $query->Where('name', 'LIKE', "%${search}%");
            });
        });
    }

    public function bankAccounts(): HasMany
    {
        return $this->hasMany(BankAccount::class, 'bank_id');
    }

    public function bankContacts(): HasMany
    {
        return $this->hasMany(BankContact::class, 'bank_id');
    }
}
