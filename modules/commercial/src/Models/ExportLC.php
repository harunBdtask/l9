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
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;
use SkylarkSoft\GoRMG\SystemSettings\Models\LienBank;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use SkylarkSoft\GoRMG\Commercial\Models\ExportInvoice;
use SkylarkSoft\GoRMG\Commercial\Models\SalesContract;
use SkylarkSoft\GoRMG\Commercial\Models\ExportLCDetail;
use SkylarkSoft\GoRMG\Commercial\Models\B2BMarginLCDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\BuyingAgentModel;
use SkylarkSoft\GoRMG\Commercial\Models\DocumentSubmissionInvoice;
use SkylarkSoft\GoRMG\Commercial\Models\PrimaryMasterContract\PrimaryMasterContract;

class ExportLC extends Model
{
    use HasFactory;
    use SoftDeletes;
    use FactoryIdTrait;

    protected $table = 'export_lc';

    protected $fillable = [
        'beneficiary_id',
        'buyer_id',
        'applicant_id',
        'notifying_party_id',
        'consignee_id',
        'lien_bank_id',
        'doc_present_days',
        'lc_date',
        'lien_date',
        'last_shipment_date',
        'lc_expiry_date',
        'year',
        'lc_value',
        'tolerance_percent',
        'btb_limit_percent',
        'foreign_comn_percent',
        'local_comn_percent',
        'internal_file_no',
        'bank_file_no',
        'lc_number',
        'currency_id',
        'issuing_bank',
        'shipping_mode',
        'pay_term',
        'tenor',
        'inco_term',
        'inco_term_place',
        'lc_source',
        'port_of_entry',
        'port_of_loading',
        'port_of_discharge',
        'transferring_bank_ref',
        'transferable',
        'replacement_lc',
        'transferring_bank',
        'negotiating_bank',
        'nominated_ship_line',
        're_imbursing_bank',
        'claim_adjustment',
        'expiry_place',
        'reason',
        'bl_clause',
        'reimbursement_clauses',
        'discount_clauses',
        'export_item_category',
        'remarks',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by',
        'buying_agent_id',
        'primary_contract_id',
        'sales_contract_id'
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

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $model->unique_id = getPrefix() . 'ELC-' . date('y') . '-' . str_pad($model->id, 6, '0', STR_PAD_LEFT);
            $model->save();
        });
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id')->withDefault();
    }

    public function applicant(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'applicant_id')->withDefault();
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'beneficiary_id')->withDefault();
    }

    public function beneficiary(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'beneficiary_id')->withDefault();
    }

    public function details(): HasMany
    {
        return $this->hasMany(ExportLCDetail::class, 'export_lc_id');
    }

    public function amendments(): HasMany
    {
        return $this->hasMany(ExportLCAmendment::class, 'contract_id');
    }

    public function exportInvoice(): HasMany
    {
        return $this->hasMany(ExportInvoice::class, 'sales_contract_id');
    }

    public function lienBank(): BelongsTo
    {
        return $this->belongsTo(LienBank::class, 'lien_bank_id')->withDefault();
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id')->withDefault();
    }
    public function primary_contract(): BelongsTo
    {
        return $this->belongsTo(PrimaryMasterContract::class, 'primary_contract_id');
    }
    public function buyingAgent()
    {
        return $this->belongsTo(BuyingAgentModel::class, 'buying_agent_id')->withDefault();
    }
    public function invoice()
    {
        return $this->hasMany(ExportInvoice::class, 'export_lc_id');
    }

    public function docSubmissionInfo()
    {
        return $this->hasManyThrough(DocumentSubmissionInvoice::class, ExportInvoice::class,'export_lc_id','export_invoice_id','id','id');
    }
    public function sales_contract(): BelongsTo
    {
        return $this->belongsTo(SalesContract::class, 'sales_contract_id','id');
    }
    public function btbLcDetails(): hasMany
    {
        return $this->hasMany(B2BMarginLCDetail::class, 'export_lc_id','id');
    }

}
