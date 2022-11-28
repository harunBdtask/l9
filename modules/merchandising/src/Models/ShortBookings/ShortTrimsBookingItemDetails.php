<?php


namespace SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;

class ShortTrimsBookingItemDetails extends Model
{
    use FactoryIdTrait;

    protected $table = 'short_trims_booking_item_details';

    protected $fillable = [
        'short_booking_id',
        'budget_unique_id',
        'qty',
        'item_id',
        'color_id',
        'size_id',
        'qty',
        'factory_id',
    ];
}
