<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models\Bookings;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TrimsVirtualStock extends Model
{

    protected $table = 'trims_virtual_stock';
    protected $primaryKey = 'id';
    protected $fillable = [
        'item_id',
        'item_color',
        'item_description',
        'item_size',
        'stock',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected static function booted()
    {

        self::creating(function ($budget) {
            $budget->factory_id = factoryId();
            $budget->created_by = Auth::id();
        });

        self::updating(function ($budget) {
            $budget->updated_by = Auth::id();
        });

        self::deleting(function ($budget) {
            $budget->deleted_by = Auth::id();
        });
    }
}
