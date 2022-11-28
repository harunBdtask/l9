<?php

namespace SkylarkSoft\GoRMG\Finance\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerBillPaymentDetail extends Model
{
    protected $fillable = [
        'customer_bill_payment_id',
        'bill_no',
        'order_no',
        'bill_date',
        'cons_rate',
        'bill_amount',
        'prev_received',
        'current_out_standing',
        'received_amount',
        'discount',
        'due_amount',
    ];
}
