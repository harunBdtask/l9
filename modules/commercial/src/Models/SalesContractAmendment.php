<?php


namespace SkylarkSoft\GoRMG\Commercial\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class SalesContractAmendment extends Model
{
    use SoftDeletes;
    use FactoryIdTrait;

    protected $table = 'sales_contract_amendments';

    protected $fillable = [
        'beneficiary_id',
        'internal_file_no',
        'year',
        'contract_number',
        'contract_value',
        'currency',
        'contract_date',
        'convertible_to',
        // 'buyer_id',
        'applicant_id',
        'notifying_party_id',
        'consignee_id',
        'lien_bank_id',
        'lien_date',
        'last_shipment_date',
        'expiry_date',
        'tolerance_percent',
        'shipping_mode',
        'pay_term',
        'tenor',
        'inco_term',
        'inco_term_place',
        'contract_source',
        'port_of_entry',
        'shipping_line',
        'doc_present_days',
        'claim_adjustment',
        'btb_limit_percent',
        'foreign_comn_percent',
        'local_comn_percent',
        'discount_clauses',
        'bl_clause',
        'export_item_category',
        'remarks',
        "contract_id",
        "amendment_date",
        "amendment_value",
        "value_changed_by",
        "port_of_loading",
        "port_of_discharge",
        "claim_adjusted_by",
        "discount_clauses",
        "bl_clause",
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * @return BelongsTo
     */
    public function salesContract(): BelongsTo
    {
        return $this->belongsTo(SalesContract::class, 'contract_id')->withDefault();
    }

    public function details(): HasMany
    {
        return $this->hasMany(SalesContractAmendmentDetail::class, 'amendment_id');
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id')->withDefault();
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'beneficiary_id')->withDefault();
    }

    /**
     * @param Request $request
     */
    public function updateSalesContract(Request $request)
    {
        $this->salesContract()->update([
            'amended' => $request->amendment_no,
            'contract_value' => $this->attributes['contract_value'],
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
            'bl_clause' => $request->bl_clause,
            'remarks' => $request->remarks,
        ]);
    }
}
