<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class ErpPackingListDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'erp_packing_list_id',
        'factory_id',
        'buyer_id',
        'order_id',
        'purchase_order_id',
        'color_id',
        'customer_name',
        'ctn_no_from',
        'ctn_no_to',
        'ctn_qty',
        'order_qty',
        'team_or_color',
        'qty_pcs_per_ctn',
        'ttl_qty_in_pcs',
        'net_weight',
        'grs_weight',
        'total_net_weight',
        'total_grs_weight',
        'length',
        'width',
        'height',
        'cbm',
        'size_ratio',
        'sizes',
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

    protected $casts = [
        'size_ratio' => Json::class,
        'sizes' => Json::class,
    ];


}
