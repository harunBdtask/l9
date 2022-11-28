<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;

class OrderColorBreakdown extends Model
{
    use  SoftDeletes;

    protected $table = 'order_color_breakdown';

    protected $fillable = [
        'order_id',
        'color_id',
        'factory_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($post) {
            $post->factory_id = factoryId();
        });
    }

    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id', 'id')->withDefault();
    }
}
