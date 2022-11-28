<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Size;

class OrderSizeBreakdown extends Model
{
    use  SoftDeletes;

    protected $table = 'order_size_breakdown';

    protected $fillable = [
        'order_id',
        'size_id',
        'factory_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function size()
    {
        return $this->belongsTo(Size::class, 'size_id', 'id');
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($post) {
            $post->factory_id = factoryId();
        });
    }
}
