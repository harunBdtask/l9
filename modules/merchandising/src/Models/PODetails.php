<?php


namespace SkylarkSoft\GoRMG\Merchandising\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class PODetails extends Model
{
    protected $table = 'purchase_order_details';

    protected $fillable = [
        'factory_id', 'buyer_id', 'order_id', 'purchase_order_id', 'garments_item_id', 'color_id', 'size_id', 'rate', 'excess_cut_percent',
        'quantity', 'article_no',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = Auth::id();
        });

        static::updating(function ($model) {
            $model->updated_by = Auth::id();
        });

        static::deleted(function ($model) {
            $model->deleted_by = Auth::id();
        });
    }

    public function factory(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Factory::class);
    }
}
