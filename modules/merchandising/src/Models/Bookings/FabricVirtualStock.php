<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models\Bookings;

use Illuminate\Database\Eloquent\Model;

class FabricVirtualStock extends Model
{
    protected $table = 'fabric_virtual_stock';
    protected $primaryKey = 'id';
    protected $fillable = [
        'composition',
        'construction',
        'gsm',
        'gmt_color',
        'item_color',
        'color_type',
        'dia',
        'stock',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
