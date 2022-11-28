<?php

namespace SkylarkSoft\GoRMG\Printembrdroplets\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class PrintEmbroideryQcInventoryChallan extends Model
{
    use FactoryIdTrait, SoftDeletes;

    protected $table = 'print_embroidery_qc_inventory_challans';

    protected $fillable = [
        'challan_no',
        'delivery_challan_no',
        'operation_name',
        'type',
        'delivery_status',
        'delivery_factory_id',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by',
        'factory_id',        
    ];

   /* protected static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            $bundle = BundleCard::find($model->bundle_card_id);
            $bundle->print_factory_receive_rejection = 0;
            $bundle->save();
        });
    }*/

    /*public static function getChallanNo()
    {
        $challan = (new static())->where([
            'status'     => 0,
            'created_by' => userId()
        ])->first();

        return $challan->challan_no ?? userId() . time();
    }*/    

    public function print_embroidery_qc_inventories()
    {
        return $this->hasMany(PrintEmbroideryQcInventory::class, 'challan_no', 'challan_no');
    }

    public function delivery_factory()
    {
        return $this->belongsTo(Factory::class, 'delivery_factory_id')->withDefault();
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }
}
