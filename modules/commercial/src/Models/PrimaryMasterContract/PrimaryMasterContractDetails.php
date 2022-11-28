<?php

namespace SkylarkSoft\GoRMG\Commercial\Models\PrimaryMasterContract;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class PrimaryMasterContractDetails extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'primary_master_contract_id',
        'style_order',
        'po',
        'description',
        'order_qty',
        'order_value',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function($model){
            $model->created_by = Auth::id();
        });

        self::updating(function ($model){
            $model->updated_by = Auth::id();
        });

        self::deleting(function ($model){
            $model->deleted_by = Auth::id();
        });
    }

}
