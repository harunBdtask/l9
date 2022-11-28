<?php


namespace SkylarkSoft\GoRMG\Commercial\Models\Imports;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImportDocumentShippingInfo extends Model
{
    use SoftDeletes;
    use ModelCommonTrait;

    protected $table = 'import_document_shipping_infos';

    protected $fillable = [
        'imp_doc_acc_id',
        'bl_cargo_no',
        'bl_cargo_date',
        'shipment_mode',
        'document_status',
        'copy_doc_receive_date',
        'original_doc_receive_date',
        'document_to_cf',
        'feeder_vessel',
        'mother_vessel',
        'eta_date',
        'ic_received_date',
        'shipping_bill_no',
        'inco_term',
        'inco_term_place',
        'port_of_loading',
        'port_of_discharge',
        'internal_file_no',
        'bill_of_entry_no',
        'psi_reference_no',
        'maturity_date',
        'container_no',
        'package_quantity',
        'factory_id',
    ];

    public function importDocument(): BelongsTo
    {
        return $this->belongsTo(ImportDocumentAcceptance::class, 'imp_doc_acc_id')->withDefault();
    }
}
