<?php

namespace SkylarkSoft\GoRMG\Commercial\Models\PrimaryMasterContract;

use App\Casts\Json;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use SkylarkSoft\GoRMG\Commercial\Options;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Commercial\Models\ExportLC;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\LienBank;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use SkylarkSoft\GoRMG\Commercial\Models\SalesContract;
use SkylarkSoft\GoRMG\Commercial\Models\B2BMarginLCDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\BuyingAgentModel;
use SkylarkSoft\GoRMG\Commercial\Models\PrimaryMasterContractAmendment\PrimaryMasterContractAmendment;

class PrimaryMasterContract extends Model
{
    use HasFactory, SoftDeletes;

    const INCO_TERMS = Options::INCO_TERMS;
    const SHIPPING_MODES = Options::SHIPPING_MODES;
    const CONTRACT_SOURCES = Options::CONTACT_SOURCES;
    const PAY_TERMS = Options::PAY_TERMS;
    const EXPORT_ITEM_CATEGORIES = Options::EXPORT_ITEM_CATEGORIES;


    protected $fillable = [
        'unique_id',
        'beneficiary_id',
        'buying_agent_id',
        'currency_id',
        'ex_contract_number',
        'contract_value',
        'inco_term',
        'inco_term_place',
        'port_of_entry',
        'port_of_loading',
        'port_of_discharge',
        'shipping_mode',
        'tolerance',
        'contract_source',
        'pay_term_id',
        'pay_term_remarks',
        'draft',
        'shipment_remarks',
        'presentation_period',
        'tenor',
        'shipping_line',
        'doc_present_days',
        'claim_adjustment',
        'btb_limit_percentage',
        'foreign_comn',
        'export_item_category_id',
        'document_required',
        'document_terms',
        'ex_cont_issue_date',
        'shipment_date',
        'expiry_date',
        'created_by',
        'updated_by',
        'deleted_by',
        'buyer_id',
        'remarks',
        'contract_date',
        'lien_bank_id'
    ];

    protected $casts = [
        'buyer_id' => Json::class
    ];

    protected $appends = [
        'buyer_names'
    ];

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->created_by = Auth::id();
        });

        static::created(function ($model) {
            $model->unique_id = getPrefix() . 'GPC-' . date('y') . '-' . str_pad($model->id, 6, '0', STR_PAD_LEFT);
            $model->save();
        });

        self::updating(function ($model) {
            $model->updated_by = Auth::id();
        });

        self::deleting(function ($model) {
            $model->deleted_by = Auth::id();
        });
    }

    public function getBuyerNamesAttribute()
    {
        $names = null;
        if ($this->buyer_id && count($this->buyer_id)){
            $names = Buyer::query()->whereIn('id', $this->buyer_id)->get(['id', 'name']);
        }
        return $names;
    }

    public function details()
    {
        return $this->hasMany(PrimaryMasterContractDetails::class, 'primary_master_contract_id', 'id');
    }

    public function beneficiary()
    {
        return $this->belongsTo(Factory::class,'beneficiary_id')->withDefault();
    }

    public function buyingAgent()
    {
        return $this->belongsTo(BuyingAgentModel::class, 'buying_agent_id')->withDefault();
    }
    public function amendments()
    {
        return $this->hasMany(PrimaryMasterContractAmendment::class, 'unique_id','unique_id');
    }
    public function salesContract()
    {
        return $this->hasMany(SalesContract::class, 'primary_contract_id','id');
    }
    public function exportLc()
    {
        return $this->hasMany(ExportLC::class, 'primary_contract_id','id');
    }
    public function btbLcDetails()
    {
        return $this->hasMany(B2BMarginLCDetail::class, 'primary_master_contract_id','id');
    }
    public function lienBank()
    {
        return $this->belongsTo(LienBank::class,'lien_bank_id')->withDefault();
    }
}
