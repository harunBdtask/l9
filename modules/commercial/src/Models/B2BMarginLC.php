<?php


namespace SkylarkSoft\GoRMG\Commercial\Models;

use App\Casts\Json;
use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Item;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;
use SkylarkSoft\GoRMG\SystemSettings\Models\LienBank;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use SkylarkSoft\GoRMG\Commercial\Models\B2BMarginLCAmendment;
use SkylarkSoft\GoRMG\SystemSettings\Models\BuyingAgentModel;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;
use SkylarkSoft\GoRMG\Commercial\Models\Imports\ImportLcCharge;
use SkylarkSoft\GoRMG\Commercial\Models\Imports\ImportDocumentAcceptance;

class B2BMarginLC extends Model
{
    use SoftDeletes;
    use ModelCommonTrait;

    protected $table = 'b_to_b_margin_lcs';

    protected $fillable = [
        'uniq_id',
        'factory_id',
        'application_date',
        'lien_bank_id',
        'item_id',
        'lc_basis',
        'pi_ids',
        'pi_value',
        'supplier_id',
        'lc_type',
        'lc_number',
        'lc_date',
        'last_shipment_date',
        'lc_expiry_date',
        'lc_value',
        'currency_id',
        'inco_term',
        'inco_term_place',
        'pay_term',
        'tenor',
        'tolerance_percentage',
        'delivery_mode',
        'doc_present_days',
        'port_of_loading',
        'port_of_discharge',
        'etd_date',
        'lca_no',
        'lcaf_no',
        'imp_form_no',
        'insurance_company',
        'cover_note_no',
        'cover_note_date',
        'psi_company',
        'maturity_from',
        'margin_deposite_percentage',
        'origin',
        'shipping_mark',
        'garments_qty',
        'unit_of_measurement_id',
        'ud_no',
        'ud_date',
        'credit_to_be_advised',
        'partial_shipment',
        'transhipment',
        'add_confirmation_req',
        'add_confirming_bank',
        'bonded_warehouse',
        'hs_code',
        'status',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'pi_ids' => Json::class,
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $model->uniq_id = getPrefix() . 'B2BMLC-' . date('y') . '-' . str_pad($model->id, 6, '0', STR_PAD_LEFT);
            $model->save();
        });
    }

    public function lienBank(): BelongsTo
    {
        return $this->belongsTo(LienBank::class, 'lien_bank_id')->withDefault([
            'name' => 'N\A',
        ]);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id')->withDefault();
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id')->withDefault();
    }

    public function details(): HasMany
    {
        return $this->hasMany(B2BMarginLCDetail::class, 'b_to_b_margin_lc_id');
    }

    public function importLcCharge(): HasMany
    {
        return $this->hasMany(ImportLcCharge::class, 'b_to_b_margin_lc_id');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id')->withDefault();
    }

    public function unitOfMeasurement(): BelongsTo
    {
        return $this->belongsTo(UnitOfMeasurement::class, 'unit_of_measurement_id')->withDefault();
    }
    public function buyingAgent()
    {
        return $this->belongsTo(BuyingAgentModel::class, 'buying_agent_id')->withDefault();
    }

    public function btbLcAmends()
    {
        return $this->hasMany(B2BMarginLCAmendment::class, 'b_to_b_margin_lc_id');
    }
    public function importLcDocument(): HasMany
    {
        return $this->hasMany(ImportDocumentAcceptance::class, 'btb_margin_lc_id');
    }
}
