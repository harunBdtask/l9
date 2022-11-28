<?php

namespace SkylarkSoft\GoRMG\Printembrdroplets\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;

class PrintDeliveryInventory extends Model
{
    use SoftDeletes, FactoryIdTrait;
    protected $table = 'print_delivery_inventories';

    protected $fillable = [
        'challan_no',
        'bundle_card_id',
        'factory_id',
        'status',
        'created_by'
    ];

    public static function getChallanNo()
    {
        return (new static)::where([
                'status'     => 0,
                'created_by' => userId()
            ])->first()->challan_no ?? time() . userId();
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            $bundle = BundleCard::find($model->bundle_card_id);
            $bundle->print_factory_delivery_rejection = 0;
            $bundle->save();
        });
    }

    public function bundle_card()
    {
        return $this->belongsTo(BundleCard::class, 'bundle_card_id');
    }

    public function print_receive_inventory()
    {
        return $this->hasOne(PrintReceiveInventory::class, 'bundle_card_id', 'bundle_card_id');
    }
}