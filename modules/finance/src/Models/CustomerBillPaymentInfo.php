<?php

namespace SkylarkSoft\GoRMG\Finance\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;

class CustomerBillPaymentInfo extends Model
{
    protected $fillable = [
        'customer_bill_payment_id',
        'received_date',
        'total_received',
        'discount_received',
        'net_received',
        'currency',
        'cons_rate',
        'bill_no',
        'details',
        'exchange_gain_loss',
    ];

    protected $casts = [
        'details' => Json::class,
    ];
}
