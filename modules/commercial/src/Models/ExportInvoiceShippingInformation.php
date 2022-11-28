<?php


namespace SkylarkSoft\GoRMG\Commercial\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExportInvoiceShippingInformation extends Model
{
    use SoftDeletes;
    use ModelCommonTrait;

    protected $table = 'export_invoice_shipping_information';

    protected $fillable = [
        'export_invoice_id',
        'bl_cargo_no',
        'bl_cargo_date',
        'original_bl_rcv_date',
        'doc_handover',
        'custom_forwarder_name',
        'etd',
        'feeder_vessel',
        'mother_vessel',
        'eta_date',
        'eta_destination',
        'ic_received_date',
        'inco_term',
        'inco_term_place',
        'shipping_bill_no',
        'shipping_bill_date',
        'port_of_entry',
        'port_of_loading',
        'port_of_discharge',
        'internal_file_no',
        'shipping_mode',
        'freight_amount_by_supplier',
        'ex_factory_date',
        'actual_ship_date',
        'freight_amount_by_buyer',
        'total_carton_qty',
        'category_no',
        'hs_code',
        'advice_date',
        'advice_amount',
        'paid_amount',
        'incentive_applicable',
        'gsp_no',
        'gsp_date',
        'yarn_cons_per_pcs',
        'co_no',
        'co_date',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by',
        'inco_term_year',
        'payment_term',
        'final_destination',
        'erc_no',
        'beneficiary_declaration',
        'lc_issue_bank',
        'bin_no',
        'pi_no',
    ];

    public function exportInvoice(): BelongsTo
    {
        return $this->belongsTo(ExportInvoice::class, 'export_invoice_id')->withDefault();
    }
}
