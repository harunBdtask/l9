<?php

namespace SkylarkSoft\GoRMG\Finance\Models;

use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int id
 * @property string account_type
 * @property string short_form
 */
class AccountType extends Model
{
    use SoftDeletes;
    use CommonModelTrait;

    protected $table = 'fi_account_types';
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
