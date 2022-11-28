<?php

namespace SkylarkSoft\GoRMG\Commercial\Models\Imports;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Commercial\Models\B2BMarginLC;
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\LienBank;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;

class ImportDocumentAcceptance extends Model
{
    use SoftDeletes;
    use ModelCommonTrait;

    protected $table = 'import_document_acceptances';

    protected $fillable = [
        'btb_margin_lc_id',
        'invoice_number',
        'invoice_date',
        'lien_bank_id',
        'shipment_date',
        'document_value',
        'lc_value',
        'currency_id',
        'bank_acc_date',
        'company_acc_date',
        'supplier_id',
        'acceptance_time',
        'bank_ref',
        'importer_id',
        'remarks',
        'retire_source_id',
        'pay_term',
        'lc_type',
        'pi_ids',
        'pi_value',
        'factory_id',
        'dipo_no',
        'goods_rcv_date',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function shippingInfo(): HasOne
    {
        return $this->hasOne(ImportDocumentShippingInfo::class, 'imp_doc_acc_id');
    }

    public function piInfos(): HasMany
    {
        return $this->hasMany(ImportDocumentPIInfo::class, 'imp_doc_acc_id');
    }

    public function supplier(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Supplier::class)->withDefault();
    }

//    public function factory(): \Illuminate\Database\Eloquent\Relations\BelongsTo
//    {
//        return $this->belongsTo(Factory::class)->withDefault();
//    }

    public function bToBMarginLC(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(B2BMarginLC::class, 'btb_margin_lc_id')->withDefault();
    }

    public function currency(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id')->withDefault();
    }

    public function lienBank(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(LienBank::class, 'lien_bank_id')->withDefault();
    }
    public function importPayment()
    {
        return $this->hasMany(ImportPayment::class, 'import_document_acceptance_id');
    }
}
