<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;

class PreBudgetDetail extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'pre_budget_id',
        'order_no',
        'image',
        'style',
        'quantity',
        'description',
        'unit_price',
        'total',
        'cm',
        'percentage',
        'cm_total',
        'remarks',
    ];

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            if ($model->image && File::exists('storage/' . $model->image)) {
                File::delete('storage/' . $model->image);
            }
        });

        static::updating(function ($model) {
            if ($model->percentage) {
                $model->cm_total = ($model->total * $model->percentage) / 100;
            }
        });

        static::updating(function ($model) {
            if (! $model->isDirty('image')) {
                return;
            }

            $prevImage = $model->getOriginal('name');

            if (is_null($prevImage)) {
                return;
            }

            if (File::exists("storage/{$prevImage}")) {
                File::delete("storage/{$prevImage}");
            }
        });
    }
}
