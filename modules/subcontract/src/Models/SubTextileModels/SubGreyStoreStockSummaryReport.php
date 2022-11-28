<?php

namespace SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels;

use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubGreyStoreStockSummaryReport extends Model
{
    use SoftDeletes;
    use CommonModelTrait;

    protected $table = 'sub_grey_store_stock_summary_reports';
    protected $primaryKey = 'id';
    protected $fillable = [
        'factory_id',
        'supplier_id',
        'sub_grey_store_id',
        'sub_textile_operation_id',
        'body_part_id',
        'fabric_composition_id',
        'fabric_type_id',
        'color_id',
        'ld_no',
        'color_type_id',
        'finish_dia',
        'dia_type_id',
        'gsm',
        'material_description',
        'receive_qty',
        'receive_return_qty',
        'issue_qty',
        'issue_return_qty',
        'receive_transfer_qty',
        'transfer_qty',
        'total_issue_roll',
        'return_issue_roll',
        'total_receive_roll',
        'return_receive_roll',
        'unit_of_measurement_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
