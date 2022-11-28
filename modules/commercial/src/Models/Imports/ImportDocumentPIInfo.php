<?php


namespace SkylarkSoft\GoRMG\Commercial\Models\Imports;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Commercial\Models\ProformaInvoice;
use SkylarkSoft\GoRMG\SystemSettings\Models\Item;

class ImportDocumentPIInfo extends Model
{
    use SoftDeletes;
    use ModelCommonTrait;

    protected $table = 'import_document_pi_infos';

    protected $fillable = [
        'imp_doc_acc_id',
        'pi_id',
        'proforma_invoices',
        'item_id',
        'pi_value',
        'current_acceptance_value',
        'mrr_value',
        'cumulative_accepted_value',
        'factory_id',
    ];

    public function importDocument(): BelongsTo
    {
        return $this->belongsTo(ImportDocumentAcceptance::class, 'imp_doc_acc_id')->withDefault();
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id')->withDefault();
    }

    public function proformaInvoice(): BelongsTo
    {
        return $this->belongsTo(ProformaInvoice::class, 'pi_id')->withDefault();
    }
}
