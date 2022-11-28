<?php

namespace SkylarkSoft\GoRMG\GeneralStore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\GeneralStore\Traits\CommonBooted;

class GsInvTransaction extends Model
{
    use HasFactory, SoftDeletes, CommonBooted;

    protected $table = 'gs_inv_transactions';

    protected $fillable = [
        'item_id',
        'brand_id',
        'qty',
        'rate',
        'trn_date',
        'trn_type',
        'trn_with',
        'voucher_id',
        'store',
        'model',
        'company_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $dates = [
        'trn_date'
    ];

    public function person()
    {
        return $this->morphTo('person', 'model', 'trn_with');
    }

    public function voucher()
    {
        return $this->belongsTo(GsInvVoucher::class, 'voucher_id', 'id');
    }

    public function item()
    {
        return $this->belongsTo(GsItem::class, 'item_id', 'id');
    }
}
