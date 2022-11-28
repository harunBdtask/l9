<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToUser;
use App\Traits\Booted;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountInfo extends Model
{
    use HasFactory, Booted, BelongsToUser;

    protected $table = 'accounts_info';
    protected $primaryKey = 'id';
    protected $fillable = [
        'accounts_id',
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
}
