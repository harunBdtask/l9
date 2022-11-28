<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Skylarksoft\Printembrdroplets\Models\PrintFactory;

class SubcontractMaster extends Model
{
    use SoftDeletes;
    protected $table = 'subcontract_master';
    protected $fillable = [
        'id',
        'buyer_id',
        'booking_no',
        'po_no',
        'other_factory_id',
        'price',
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

    public function subcontract_details()
    {
        return $this->hasMany(SubcontractDetail::class, 'subcontract_master_id');
    }

    public function buyer()
    {
        return $this->belongsTo(Buyer::class, 'buyer_id', 'id')->withDefault();
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'booking_no', 'id')->withDefault();
    }

    public function factory()
    {
        return $this->belongsTo(PrintFactory::class, 'factory_id', 'id');
    }
}
