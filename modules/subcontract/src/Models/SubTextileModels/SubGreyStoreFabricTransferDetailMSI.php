<?php

namespace SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubGreyStoreFabricTransferDetailMSI extends Model
{
    use SoftDeletes;

    protected $table = 'sub_grey_store_fabric_transfer_detail_msi';
    protected $primaryKey = 'id';
    protected $fillable = [
        'fabric_transfer_id',
        'transfer_detail_id',
        'transfer_date',
        'form_operation_id',
        'form_body_part_id',
        'form_fabric_composition_id',
        'form_fabric_type_id',
        'form_color_id',
        'form_ld_no',
        'form_color_type_id',
        'form_finish_dia',
        'form_dia_type_id',
        'form_gsm',
        'form_unit_of_measurement_id',
        'form_fabric_description',
        'form_total_roll',
        'to_operation_id',
        'to_body_part_id',
        'to_fabric_composition_id',
        'to_fabric_type_id',
        'to_color_id',
        'to_color_type_id',
        'to_ld_no',
        'to_finish_dia',
        'to_dia_type_id',
        'to_gsm',
        'to_fabric_description',
        'to_unit_of_measurement_id',
        'to_total_roll',
        'transfer_qty',
    ];

    public function transfer(): BelongsTo
    {
        return $this->belongsTo(SubGreyStoreFabricTransfer::class, 'transfer_id')
            ->withDefault();
    }

    public function transferDetail(): BelongsTo
    {
        return $this->belongsTo(SubGreyStoreFabricTransferDetail::class, 'transfer_detail_id')
            ->withDefault();
    }
}
