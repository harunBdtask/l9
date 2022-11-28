<?php


namespace SkylarkSoft\GoRMG\Merchandising\Models\Bookings;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;

class TrimsBookingItemDetails extends Model
{
    use FactoryIdTrait;

    protected $table = 'trims_booking_item_details';

    protected $fillable = [
        'booking_id',
        'budget_unique_id',
        'qty',
        'po_no',
        'item_id',
        'color_id',
        'size_id',
        'factory_id',
    ];
}
