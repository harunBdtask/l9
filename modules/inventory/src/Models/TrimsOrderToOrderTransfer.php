<?php

namespace SkylarkSoft\GoRMG\Inventory\Models;

use App\Casts\Json;
use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrimsOrderToOrderTransfer extends Model
{
    use ModelCommonTrait, SoftDeletes;

    protected $table = 'trims_order_to_order_transfers';

    protected $primaryKey = 'id';

    protected $fillable = [
        'factory_id',
        'challan_no',
        'transfer_date',
        'from_order',
        'to_order',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'from_order' => Json::class,
        'to_order' => Json::class,
    ];
}
