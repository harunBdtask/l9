<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;

class GarmentsSample extends Model
{
    protected $fillable = [
        'buyer_id',
        'factory_id',
        'type',
        'name',
        'status',
    ];

    protected $casts = [
        'buyer_id' => Json::class,
    ];

    const STATUSES = [
        'active' => 'Active',
        'inactive' => 'Inactive',
    ];

    const TYPES = [
        'pp' => 'PP',
        'final' => 'Final',
        'tag' => 'Tag',
        'production' => 'Production',
        'development' => 'Development',
        'fit' => 'FIT',
        'photo' => 'Photo',
        'proto' => 'Proto',
        'counter' => 'Counter',
        'size_set' => 'Size Set',
        'others' => 'Others',
    ];

    public function factory()
    {
        return $this->belongsTo(Factory::class);
    }

    public function commaSeparatedBuyers()
    {
        return Buyer::whereIn('id', $this->attributes['buyer_id'])->pluck('name')->implode(', ');
    }
}
