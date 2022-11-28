<?php

namespace SkylarkSoft\GoRMG\Finance\Models;

use App\Casts\Json;
use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AcSupplier extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $table = 'account_suppliers';
    protected $primaryKey = 'id';
    protected $fillable = [
        'control_account_id',
        'supplier_no',
        'group_company',
        'ledger_account_id',
        'sub_ledger_account_id',
        'name',
        'head_address',
        'branch_address',
        'contract_information',
        'note',
        'attachment',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'contract_information' => Json::class,
    ];

    protected $appends = ['ledger_account_name', 'sub_ledger_account_name'];

    public function getLedgerAccountNameAttribute()
    {
        return $this->ledgerAccount->name ?? null;
    }

    public function getSubLedgerAccountNameAttribute()
    {
        return $this->subLedgerAccount->name ?? null;
    }

    public function controlAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'control_account_id')->withDefault();
    }

    public function ledgerAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'ledger_account_id')->withDefault();
    }

    public function subLedgerAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'sub_ledger_account_id')->withDefault();
    }

    public function taxVatInfo(): HasOne
    {
        return $this->hasOne(AcSupplierTaxVatInfo::class, 'account_supplier_id');
    }

    public function payment(): HasOne
    {
        return $this->hasOne(AcSupplierPayment::class, 'account_supplier_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(AcSupplierItem::class, 'account_supplier_id');
    }

    public static function boot()
    {
        parent::boot();
        static::created(function ($model) {
            $model->supplier_no = str_pad($model->id, 8, '0', STR_PAD_LEFT);
            $model->save();
        });
    }
}
