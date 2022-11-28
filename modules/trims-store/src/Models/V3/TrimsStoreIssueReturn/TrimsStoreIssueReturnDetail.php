<?php

namespace SkylarkSoft\GoRMG\TrimsStore\Models\V3\TrimsStoreIssueReturn;

use App\Models\BelongsToBin;
use App\Models\BelongsToBuyer;
use App\Models\BelongsToFloor;
use App\Models\BelongsToRack;
use App\Models\BelongsToRoom;
use App\Models\BelongsToShelf;
use App\Models\BelongsToStore;
use App\Models\BelongsToSupplier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\TrimsStore\Traits\CommonBooted;

class TrimsStoreIssueReturnDetail extends Model
{
    use SoftDeletes;
    use CommonBooted;
    use BelongsToBuyer;
    use BelongsToSupplier;
    use BelongsToStore;
    use BelongsToFloor;
    use BelongsToRoom;
    use BelongsToRack;
    use BelongsToShelf;
    use BelongsToBin;

    protected $table = 'v3_trims_store_issue_return_details';
    protected $primaryKey = 'id';
    protected $fillable = [
        'unique_id',
        'trims_store_issue_return_id',
        'factory_id',
        'buyer_id',
        'style_id',
        'po_numbers',
        'booking_id',
        'booking_no',
        'garments_item_id',
        'item_code',
        'item_id',
        'sensitivity_id',
        'supplier_id',
        'brand_name',
        'item_description',
        'color_id',
        'size_id',
        'order_qty',
        'wo_qty',
        'issue_return_qty',
        'uom_id',
        'currency_id',
        'rate',
        'exchange_rate',
        'amount',
        'transaction_date',
        'floor_id',
        'room_id',
        'rack_id',
        'shelf_id',
        'bin_id',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
