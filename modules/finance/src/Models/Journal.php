<?php

namespace SkylarkSoft\GoRMG\Finance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Journal extends Model
{
    protected $table = 'journal';

    const DEBIT = 'dr', CREDIT = 'cr';

    protected $fillable = [
    	'trn_date',
    	'account_id',
    	'trn_type',
    	'unit_id',
    	'cost_center_id',
    	'currency_id',
    	'conversion_rate',
    	'fc',
    	'trn_amount',
    	'particulars',
    	'voucher_id',
    	'posted_by',
        'factory_id'
    ];

    public static $trnTypes = ['dr', 'cr'];

    protected $dates = [
        'trn_date'
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class, 'voucher_id');
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(AcCompany::class, 'factory_id')->withDefault();
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(AcUnit::class, 'unit_id')->withDefault();
    }

    public function costCenter(): BelongsTo
    {
        return $this->belongsTo(AcDepartment::class, 'cost_center_id')->withDefault();
    }
}
