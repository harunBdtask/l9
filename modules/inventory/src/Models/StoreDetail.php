<?php


namespace SkylarkSoft\GoRMG\Inventory\Models;

use App\Models\BelongsToFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreDetail extends Model
{
    use BelongsToFactory;

    protected $fillable = [
        'factory_id',
        'location',
        'store_id',
        'floor',
        'floor_sequence',
        'room',
        'room_sequence',
        'rack',
        'rack_sequence',
        'shelf',
        'shelf_sequence',
        'bin',
        'bin_sequence',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id')->withDefault();
    }
}
