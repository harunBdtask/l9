<?php

namespace SkylarkSoft\GoRMG\Finance\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use SkylarkSoft\GoRMG\SystemSettings\Models\Company;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use SkylarkSoft\GoRMG\Finance\Models\SupplierBillPaymentBillNo;

class SupplierBillPayment extends Model
{
    use HasFactory;

    protected $table = 'supplier_bill_payments';
    protected $fillable = [
        'group_id',
        'company_id',
        'project_id',
        'supplier_id',
        'currency_id',
        'payment_date',
        'total_paid_amount',
        'total_discount',
        'total_due_amount',
        'total_net_payment',
        'pay_currency_id',
        'pay_con_rate',
        'total_bill_amount',
        'final_paid_amount',
        'final_paid_amount_bdt',
        'final_gain_loss',
        'details',
        'payments'
    ];

    protected $casts = [
        'details' => Json::class,
        'payments' => Json::class,
    ];

    public function scopeSearch(Builder $query, $search)
    {
        $query->when($search, function (Builder $builder) use ($search) {
            $builder->whereDate('payment_date', $search);
        });
    }

    public function billNos()
    {
        return $this->hasMany(SupplierBillPaymentBillNo::class, 'bill_payment_id','id');
    }

    public function group()
    {
        return $this->belongsTo(Company::class, 'group_id','id');
    }

    public function company()
    {
        return $this->belongsTo(Factory::class, 'company_id','id');
    }
}