<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Item;

class OrderItemDetail extends Model
{
    use SoftDeletes;
    protected $table = 'order_item_details';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'order_id',
        'item_id',
        'item_category',
        'item_description',
        'fabric_description',
        'fabrication',
        'composition_fabric_id',
        'gsm',
        'unit_price',
        'quantity',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($post) {
            $post->created_by = userId();
            $post->factory_id = factoryId();
        });

        static::deleted(function ($post) {
            $post->deleted_by = userId();
        });
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id')->withDefault();
        ;
    }

    public function fabrication()
    {
        return $this->belongsTo(Fabric_composition::class, 'composition_fabric_id', 'id')->withDefault();
        ;
    }

    public function fabric_composition()
    {
        return $this->belongsTo(Fabric_composition::class, 'composition_fabric_id', 'id')->withDefault();
        ;
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id')->withDefault();
    }

    public function factory()
    {
        return $this->belongsTo(Factory::class, 'factory_id', 'id')->withDefault();
    }

    public function getFabricFabricationAttribute()
    {
        return $this->fabric_description. ' '. $this->fabric_composition->yarn_composition;
    }
}
