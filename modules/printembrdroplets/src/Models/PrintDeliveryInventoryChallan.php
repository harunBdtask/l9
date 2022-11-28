<?php

namespace SkylarkSoft\GoRMG\Printembrdroplets\Models;

use App\FactoryIdTrait;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrintDeliveryInventoryChallan extends Model
{
    use SoftDeletes, FactoryIdTrait, CascadeSoftDeletes;

    protected $table = 'print_delivery_inventory_challans';
    protected $cascadeDeletes = ['print_delivery_inventories'];

    protected $fillable = [
        'challan_no',
        'bag',
        'part_id',
        'print_factory_delivery_id',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function print_delivery_inventories()
    {
        return $this->hasMany(PrintDeliveryInventory::class, 'challan_no', 'challan_no');
    }

    public function inventories()
    {
        return $this->print_delivery_inventories();
    }

    public function bundleCardIds()
    {
        return $this->print_delivery_inventories()->pluck('bundle_card_id')->all();
    }

    public function createdBy()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\User', 'created_by')->withDefault();
    }
}