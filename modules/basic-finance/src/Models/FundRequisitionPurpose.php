<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FundRequisitionPurpose extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $table = 'bf_fund_requisition_purposes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'purpose',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
