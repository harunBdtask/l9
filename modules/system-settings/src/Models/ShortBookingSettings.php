<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ShortBookingSettings extends Model
{
    protected $table = 'short_booking_settings';
    protected $primaryKey = 'id';
    protected $fillable = [
        'fabric_percentage',
        'trims_percentage',
        'factory_id',
        'created_by',
        'updated_by',
    ];

    public static function booted()
    {
        static::updating(function ($model) {
            $model->created_by = Auth::id();
            $model->updated_by = Auth::id();
        });
    }
}
