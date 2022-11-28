<?php

namespace SkylarkSoft\GoRMG\Finance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupplierBillPaymentBillNo extends Model
{
    use HasFactory;

    protected $table = 'supplier_bill_payment_bill_nos';
    protected $fillable = [
        'bill_payment_id',
        'bill_entry_id'
    ];

    public function billEntry(): BelongsTo
    {
        return $this->belongsTo(SupplierBillEntry::class, 'bill_entry_id', 'id');
    }
    public function billPayment(): BelongsTo
    {
        return $this->belongsTo(SupplierBillPayment::class, 'bill_payment_id', 'id');
    }
    
}
