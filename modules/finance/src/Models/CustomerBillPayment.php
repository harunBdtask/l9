<?php

namespace SkylarkSoft\GoRMG\Finance\Models;

use App\Casts\Json;
use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use SkylarkSoft\GoRMG\Finance\Services\CurrencyService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Company;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class CustomerBillPayment extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $fillable = [
        'group_id',
        'company_id',
        'project_id',
        'currency_id',
        'customer_id',
        'bill_nos',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'bill_nos' => Json::class,
    ];

    protected $appends = [
        'currency_name',
    ];

    public function getCurrencyNameAttribute(): string
    {
        return isset($this->attributes['currency_id']) ?
            collect(CurrencyService::currencies())->where('id', $this->attributes['currency_id'])->first()['name'] ?? ''
            : '';
    }

    public function details(): HasMany
    {
        return $this->hasMany(CustomerBillPaymentDetail::class, 'customer_bill_payment_id');
    }

    public function paymentInfo(): HasOne
    {
        return $this->hasOne(CustomerBillPaymentInfo::class, 'customer_bill_payment_id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'group_id')->withDefault();
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'company_id')->withDefault();
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id')->withDefault();
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'customer_id')->withDefault();
    }
}
