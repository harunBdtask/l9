<?php

namespace SkylarkSoft\GoRMG\Commercial\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class ExportLCAmendment extends Model
{
    use HasFactory;
    use SoftDeletes;
    use FactoryIdTrait;

    protected $table = "export_lc_amendments";

    protected $fillable = [
        'beneficiary_id',
        // 'buyer_id',
        'lien_bank_id',
        'amendment_no',
        'lc_date',
        'lc_expiry_date',
        'amendment_date',
        'last_shipment_date',
        'expiry_date',
        'lc_value',
        'tolerance_percent',
        'amendment_value',
        'claim_adjustment',
        'currency',
        'replacement_lc',
        'value_changed_by',
        'shipping_mode',
        'inco_term_place',
        'port_of_entry',
        'port_of_loading',
        'port_of_discharge',
        'pay_term',
        'tenor',
        'claim_adjusted_by',
        'discount_clauses',
        'remarks',
        'export_item_category',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by ',
        'internal_file_no',
        'lc_number',
        'year',
        'contract_id',
    ];

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id')->withDefault();
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'beneficiary_id')->withDefault();
    }

    public function details(): HasMany
    {
        return $this->hasMany(ExportLCAmendmetDetail::class, 'amendment_id');
    }

    public function ExportLc(): BelongsTo
    {
        return $this->belongsTo(ExportLC::class, 'contract_id')->withDefault();
    }

    public function updateExportLC(Request $request)
    {
        $this->ExportLc()->update([
            'amended' => $request->amendment_no,
            'lc_value' => $this->attributes['lc_value'],
            'claim_adjustment' => $this->attributes['claim_adjustment'],
            'last_shipment_date' => $request->last_shipment_date,
            'expiry_date' => $request->expiry_date,
            'shipping_mode' => $request->shipping_mode,
            'inco_term' => $request->inco_term,
            'inco_term_place' => $request->inco_term_place,
            'port_of_entry' => $request->port_of_entry,
            'port_of_loading' => $request->port_of_loading,
            'port_of_discharge' => $request->port_of_discharge,
            'pay_term' => $request->pay_term,
            'tenor' => $request->tenor,
            'discount_clauses' => $request->discount_clauses,
            'remarks' => $request->remarks,
        ]);
    }
}
