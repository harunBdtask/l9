<?php


namespace SkylarkSoft\GoRMG\Merchandising\Models\WorkOrders;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;

class EmbellishmentBookingItemDetails extends Model
{
    use FactoryIdTrait;

    protected $table = 'embellishment_booking_item_details';

    protected $fillable = [
        'embellishment_work_order_id',
        'budget_unique_id',
        'po_no',
        'qty',
        'item_id',
        'item_type_id',
        'color_id',
        'size_id',
        'qty',
        'factory_id',
    ];
}
