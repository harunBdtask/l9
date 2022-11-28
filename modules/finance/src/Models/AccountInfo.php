<?php

namespace SkylarkSoft\GoRMG\Finance\Models;

use App\Traits\Booted;
use App\Traits\BelongsToUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountInfo extends Model
{
    use HasFactory, Booted, BelongsToUser, SoftDeletes;

    protected $table = 'fi_accounts_info';
    protected $primaryKey = 'id';
    protected $fillable = [
        'account_id',
        'parent_account_id',
        'group_account_id',
        'control_account_id',
        'ledger_account_id',
        'created_by',
        'updated_by',
    ];

    public function parentAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'parent_account_id')
            ->withDefault(['name' => 'N\A']);
    }

    public function groupAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'group_account_id')
            ->withDefault(['name' => 'N\A']);
    }

    public function controlAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'control_account_id')
            ->withDefault(['name' => 'N\A']);
    }

    public function ledgerAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'ledger_account_id')
            ->withDefault(['name' => 'N\A']);
    }
    public function controlLedgerAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'accounts_id', 'id');
    }
}
