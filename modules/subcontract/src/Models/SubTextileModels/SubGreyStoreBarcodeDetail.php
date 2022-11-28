<?php

namespace SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels;

use App\Models\BelongsToBuyer;
use App\Models\BelongsToFactory;
use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubGreyStoreBarcodeDetail extends Model
{
    use SoftDeletes;
    use CommonModelTrait;
    use BelongsToFactory;
    use BelongsToBuyer;

    protected $table = 'sub_grey_store_barcode_details';
    protected $primaryKey = 'id';
    protected $fillable = [
        'factory_id',
        'sub_grey_store_receive_id',
        'sub_grey_store_receive_detail_id',
        'supplier_id',
        'sub_textile_order_id',
        'sub_textile_order_detail_id',
        'sub_grey_store_id',
        'roll_id',
        'barcode_qty',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function subDyeingOrder(): BelongsTo
    {
        return $this->belongsTo(SubTextileOrder::class, 'sub_textile_order_id', 'id')
            ->withDefault();
    }

    public function subDyeingOrderDetail(): BelongsTo
    {
        return $this->belongsTo(SubTextileOrderDetail::class, 'sub_textile_order_detail_id', 'id')
            ->withDefault();
    }
}
