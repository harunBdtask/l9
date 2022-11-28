<?php

namespace SkylarkSoft\GoRMG\Finance\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcSupplierTaxVatInfo extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $table = 'account_supplier_tax_vat_infos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'account_supplier_id',
        'tax_tin_number',
        'tax_rate',
        'vat_tin_number',
        'vat_rate',
        'vat_type',
    ];
}
