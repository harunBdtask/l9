<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PqCommissionDetail extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'pq_commission_details';

    protected $fillable = [
        'quotation_id',
        'particular',
        'commission_base',
        'commission_rate',
        'amount',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $dates = ['deleted_at'];
    protected $appends = [
        "particular_name",
        "commission_basis_name",
    ];

    const PARTICULARS = [
        '1' => 'Foreign',
        '2' => 'Local',
    ];

    const STATUS = [
        '1' => 'Active',
        '2' => 'Inactive',
    ];

    const COMMISSION_BASES = [
        '1' => 'In Percentage',
        '2' => 'Per Dzn',
        '3' => 'Per Pcs',
    ];

    public function getParticularNameAttribute(): string
    {
        return self::PARTICULARS[$this->particular];
    }

    public function getCommissionBasisNameAttribute(): string
    {
        return self::COMMISSION_BASES[$this->commission_base];
    }

    public function priceQuotation()
    {
        return $this->belongsTo(PriceQuotation::class, 'quotation_id', 'quotation_id')->withDefault();
    }
}
