<?php


namespace SkylarkSoft\GoRMG\Merchandising\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Skylarksoft\Systemsettings\Models\User;

class ShipmentDateHistory extends Model
{
    use SoftDeletes;
    protected $table = 'order_shipment_related_date_histories';

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
