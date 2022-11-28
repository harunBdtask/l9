<?php

namespace SkylarkSoft\GoRMG\TrimsStore\Models\V3\TrimsStoreIssueReturn;

use App\Models\BelongsToFactory;
use App\Models\BelongsToStore;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\TrimsStore\Services\V3\UID\TrimsStoreIssueReturn\TrimsStoreIssueReturnService;
use SkylarkSoft\GoRMG\TrimsStore\Traits\CommonBooted;

class TrimsStoreIssueReturn extends Model
{
    use SoftDeletes;
    use CommonBooted;
    use BelongsToFactory;
    use BelongsToStore;

    const SOURCES = [
        1 => 'Based on Booking',
        2 => 'In-House',
        3 => 'Out-Bound',
        4 => 'Import',
    ];

    const RETURN_TYPES = [
        1 => 'Manually',
        2 => 'Barcode',
    ];

    const RETURN_BASIS = [
        1 => 'Rcv Challan Basis',
        2 => 'Independent Basis',
        3 => 'Input Challan Basis',
    ];

    const PAY_MODES = [
        1 => 'Based On Booking',
        2 => 'Manually',
        3 => 'Barcode',
        4 => 'Credit',
        5 => 'Import',
    ];

    const READY_TO_APPROVE = [
        1 => 'No',
        2 => 'Yes',
    ];

    protected $table = 'v3_trims_store_issue_returns';
    protected $primaryKey = 'id';
    protected $fillable = [
        'unique_id',
        'factory_id',
        'return_source_id',
        'store_id',
        'return_basis_id',
        'return_challan_no',
        'input_challan_no',
        'issue_return_date',
        'pi_number',
        'pi_rcv_date',
        'lc_no',
        'lc_rcv_date',
        'return_to',
        'pay_mode_id',
        'return_type_id',
        'returned_source_id',
        'ready_to_approve_id',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public static function booted()
    {
        static::saving(function ($model) {
            if (! $model->id && in_array('created_by', $model->getFillable())) {
                $model->unique_id = TrimsStoreIssueReturnService::generateUniqueId();
            }
        });
    }
}
