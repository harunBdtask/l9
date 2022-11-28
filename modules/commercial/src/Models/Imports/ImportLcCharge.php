<?php

namespace SkylarkSoft\GoRMG\Commercial\Models\Imports;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Commercial\Models\B2BMarginLC;

class ImportLcCharge extends Model
{
    use SoftDeletes;
    use ModelCommonTrait;

    protected $table = 'import_lc_charges';

    protected $fillable = [
        'b_to_b_margin_lc_id',
        'pay_date',
        'pay_head_id',
        'charge_for_id',
        'amount',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $dates = ['deleted_at'];

    public function b2bMarginLc()
    {
        return $this->belongsTo(B2BMarginLC::class, 'b_to_b_margin_lc_id')->withDefault();
    }
}
