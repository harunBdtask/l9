<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class ErpPackingList extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'factory_id',
        'buyer_id',
        'order_id',
        'purchase_order_id',
        'style_name',
        'po_no',
        'hanger_name',
        'vessel_name',
        'shipping_mark',
        'port_of_landing',
        'port_of_discharge',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->created_by = Auth::id();
        });

        self::updating(function ($model) {
            $model->updated_by = Auth::id();
        });

        self::deleted(function ($model) {
            $model->deleted_by = Auth::id();
            $model->save();
        });
    }

    public function details()
    {
        return $this->hasMany(ErpPackingListDetail::class, 'erp_packing_list_id', 'id');
    }

    public function buyer()
    {
        return $this->belongsTo(Buyer::class, 'buyer_id')->withDefault();
    }

    public function factory()
    {
        return $this->belongsTo(Factory::class, 'factory_id')->withDefault();
    }

}
