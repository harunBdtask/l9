<?php

namespace SkylarkSoft\GoRMG\Commercial\Models\PrimaryMasterContractAmendment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\SystemSettings\Models\BuyingAgentModel;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class PrimaryMasterContractAmendment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'amend_no',
        'amend_date',
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
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->created_by = Auth::id();
            $model->amend_no = $model->amend_no + 1;
        });

        self::updating(function ($model) {
            $model->updated_by = Auth::id();
        });

        self::deleting(function ($model) {
            $model->deleted_by = Auth::id();
        });
    }

    public function details()
    {
        return $this->hasMany(PrimaryMasterContractAmendmentDetail::class, 'primary_master_contract_amendment_id', 'id');
    }

    public function beneficiary()
    {
        return $this->belongsTo(Factory::class, 'beneficiary_id')->withDefault();
    }

    public function buyingAgent()
    {
        return $this->belongsTo(BuyingAgentModel::class, 'buying_agent_id')->withDefault();
    }
}
