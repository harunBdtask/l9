<?php

namespace SkylarkSoft\GoRMG\Finance\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcSupplierPayment extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $table = 'account_supplier_payments';
    protected $primaryKey = 'id';
    protected $fillable = [
        'account_supplier_id',
        'mode',
        'cheque_name',
        'condition',
        'payment_after',
        'days',
    ];
}
