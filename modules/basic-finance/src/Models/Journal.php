<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class Journal extends Model
{
    protected $table = 'bf_journal';

    const DEBIT = 'dr', CREDIT = 'cr';

    protected $fillable = [
    	'trn_date',
    	'account_id',
        'account_code',
    	'trn_type',
        'project_id',
    	'unit_id',
    	'department_id',
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
        return $this->belongsTo(Factory::class, 'factory_id')->withDefault();
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id')->withDefault();
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id')->withDefault();
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id')->withDefault();
    }

    public function cost_center(): BelongsTo
    {
        return $this->belongsTo(CostCenter::class, 'cost_center_id')->withDefault();
    }
}
