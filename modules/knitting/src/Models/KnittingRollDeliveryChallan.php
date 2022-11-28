<?php

namespace SkylarkSoft\GoRMG\Knitting\Models;

use App\ModelCommonTrait;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KnittingRollDeliveryChallan extends Model
{
    use SoftDeletes, ModelCommonTrait, CascadeSoftDeletes;

    protected $table = 'knitting_roll_delivery_challans';

    protected $fillable = [
        'challan_no',
        'challan_date',
        'destination',
        'driver_name',
        'vehicle_no',
        'remarks',
        'delivery_qty',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $dates = ['deleted_at'];

    protected $cascadeDeetes = [
        'challanDetails'
    ];

    public function challanDetails()
    {
        return $this->hasMany(KnittingRollDeliveryChallanDetail::class, 'challan_no', 'challan_no');
    }
}
