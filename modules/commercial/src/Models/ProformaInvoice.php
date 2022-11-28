<?php


namespace SkylarkSoft\GoRMG\Commercial\Models;

use App\Constants\ApplicationConstant;
use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Commercial\Models\Imports\ImportDocumentPIInfo;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Item;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;

class ProformaInvoice extends Model
{
    use SoftDeletes;
    use ModelCommonTrait;

    protected $table = 'proforma_invoices';

    const LC_FROM = [1 => 'Local', 2 => 'Foreign'];

    protected $fillable = [
        'supplier_id',
        'approval_user_id',
        'beneficiary',
        'file_path',
        'bill_entry_file',
        'import_docs',
        'goods_rcv_status',
        'item_category',
        'source',
        'hs_code',
        'tenor',
        'importer_id',
        'indentor_name',
        'pi_receive_date',
        'last_shipment_date',
        'pi_validity_date',
        'currency',
        'lc_group_no',
        'lc_receive_date',
        'pay_term',
        'pi_basis',
        'internal_file_no',
        'pi_no',
        'pi_for',
        'priority',
        'ready_to_approve',
        'details',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by',
        'b_to_b_margin_lc_id',
        'lc_from',
        'country_id',
        'pi_created_date'
    ];

    public function attachB2BMarginLCId($id)
    {
        $this->attributes['b_to_b_margin_lc_id'] = $id;
        $this->save();
    }

    public function setDetailsAttribute($value)
    {
        $this->attributes['details'] = json_encode($value);
    }

    public function getDetailsAttribute($value)
    {
        return json_decode($value);
    }

    public function getSource()
    {
        if (array_key_exists($this->source, ApplicationConstant::SOURCES)) {
            return ApplicationConstant::SOURCES[$this->source];
        }

        return '';
    }

    public function importer(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'importer_id')->withDefault();
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id')->withDefault();
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_category');
    }

    public function importDocumentPiInfos()
    {
        return $this->hasMany(ImportDocumentPIInfo::class, 'pi_id');
    }

    public function btbLc()
    {
        return $this->hasMany(B2BMarginLC::class, 'pi_ids');
    }

    public function details(): HasMany
    {
        return $this->hasMany(ProformaInvoiceDetails::class, 'invoice_id', 'id');
    }
}
