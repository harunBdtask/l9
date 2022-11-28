<?php

namespace SkylarkSoft\GoRMG\Commercial\Models;

use App\Casts\Json;
use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use Illuminate\Database\Eloquent\Relations\HasMany;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\SystemSettings\Models\LienBank;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use SkylarkSoft\GoRMG\Commercial\Models\B2BMarginLCDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\BuyingAgentModel;
use SkylarkSoft\GoRMG\Commercial\Models\PrimaryMasterContract\PrimaryMasterContract;

class SalesContract extends Model
{
    use HasFactory;
    use SoftDeletes;
    use FactoryIdTrait;

    protected $table = "sales_contracts";

    protected $fillable = [
        'amendment_no',
        'beneficiary_id',
        'internal_file_no',
        'year',
        'contract_number',
        'contract_value',
        'currency_id',
        'contract_date',
        'convertible_to',
        'buyer_id',
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
        'port_of_loading',
        'shipping_line',
        'doc_present_days',
        'claim_adjustment',
        'btb_limit_percent',
        'foreign_comn_percent',
        'local_comn_percent',
        'discount_clauses',
        'bl_clause',
        'export_item_category',
        'amended',
        'remarks',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by',
        'bank_file_no',
        'port_of_discharge',
        'b_to_b_margin_lc_id',
        'buying_agent_id',
        'primary_contract_id',
        'hs_code'
    ];

    protected $casts = [
        'buyer_id' => Json::class
    ];
    protected $appends = [
        'buyer_names'
    ];
    public function getBuyerNamesAttribute()
    {
        $names = null;
        if ($this->buyer_id && is_array($this->buyer_id)){
            $names = Buyer::query()->whereIn('id', $this->buyer_id)->get(['id', 'name']);
        }else{
            $names = Buyer::query()->where('id', $this->buyer_id)->get(['id', 'name']);
        }
        return $names;

    }
    

    public function attachB2BMarginLCId($id)
    {
        $this->attributes['b_to_b_margin_lc_id'] = $id;
        $this->save();
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $model->unique_id = getPrefix() . 'SC-' . date('y') . '-' . str_pad($model->id, 6, '0', STR_PAD_LEFT);
            $model->save();
        });
    }

    /**
     * @return BelongsTo
     */
    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id')->withDefault();
    }

    public function notifyParty(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'notifying_party_id')->withDefault();
    }

    public function details(): HasMany
    {
        return $this->hasMany(SalesContractDetail::class, 'sales_contract_id');
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'beneficiary_id')->withDefault();
    }

    public function amendments(): HasMany
    {
        return $this->hasMany(SalesContractAmendment::class, 'contract_id');
    }

    public function beneficiary(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'beneficiary_id')->withDefault();
    }

    public function applicant(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'applicant_id')->withDefault();
    }

    public function exportInvoice(): HasMany
    {
        return $this->hasMany(ExportInvoice::class, 'export_lc_id');
    }

    public function lienBank(): BelongsTo
    {
        return $this->belongsTo(LienBank::class, 'lien_bank_id')->withDefault();
    }
    public function primary_contract(): BelongsTo
    {
        return $this->belongsTo(PrimaryMasterContract::class, 'primary_contract_id');
    }
    public function buyingAgent()
    {
        return $this->belongsTo(BuyingAgentModel::class, 'buying_agent_id')->withDefault();
    }
    public function btbLcDetails(): hasMany
    {
        return $this->hasMany(B2BMarginLCDetail::class, 'sales_contract_id','id');
    }
}
