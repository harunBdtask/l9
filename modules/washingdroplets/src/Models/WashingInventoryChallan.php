<?php

namespace SkylarkSoft\GoRMG\Washingdroplets\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\FactoryIdTrait;

class WashingInventoryChallan extends Model
{
    use FactoryIdTrait;
    use SoftDeletes;

    protected $fillable = [
        'washing_challan_no',       
        'bag',        
        'print_wash_factory_id',
        'security_staus'
    ];

    protected $dates = ['deleted_at'];


    public function printWashFactory()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\PrintFactory', 'print_wash_factory_id', 'id');
    }

    public function washings()
    {
        return $this->hasMany(Washing::class, 'washing_challan_no', 'washing_challan_no');
    }

}
