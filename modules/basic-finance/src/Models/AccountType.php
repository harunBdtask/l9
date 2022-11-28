<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Models;

use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccountType extends Model
{
    use SoftDeletes;
    use CommonModelTrait;

    protected $table = 'account_types';
    protected $primaryKey = 'id';
    protected $fillable = [
        'account_type',
        'short_form',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function bankAccounts(): HasMany
    {
        return $this->hasMany(BankAccount::class, 'account_type_id');
    }
}
