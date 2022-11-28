<?php

namespace SkylarkSoft\GoRMG\Commercial\Models\LcRequest;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;

class LCRequest extends Model
{
    use HasFactory;

    protected $table = 'lc_requests';
    protected $fillable = [
        'factory_id',
        'buyer_id',
        'unique_id',
        'request_date',
        'open_date',
        'attention',
        'approve_status',
        'remarks',
    ];

    public static function boot()
    {
        parent::boot();
        static::created(function ($model) {
            $model->unique_id = getPrefix(). 'LC-Req-'. date('y'). '-'. str_pad($model->id, 6, 0, STR_PAD_LEFT);
            $model->save();
        });
    }

    public function buyer()
    {
        return $this->belongsTo(Buyer::class,'buyer_id')->withDefault();
    }

    public function details()
    {
        return $this->hasMany(LCRequestDetails::class,'lc_request_id');
    }


}
