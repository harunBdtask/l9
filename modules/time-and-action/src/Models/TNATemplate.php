<?php

namespace SkylarkSoft\GoRMG\TimeAndAction\Models;

use App\ModelCommonTrait;
use App\Models\BelongsToFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\TimeAndAction\Filters\Filter;

class TNATemplate extends Model
{
    use ModelCommonTrait;

    protected $table = 'tna_templates';

    protected $fillable = [
        'template_id',
        'factory_id',
        'buyer_id',
        'lead_time',
        'tna_for',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id')->withDefault();
    }

    public function details(): HasMany
    {
        return $this->hasMany(TNATemplateDetail::class, 'template_id', 'id');
    }

    public static function booted()
    {
        static::created(function ($model) {
            $model->template_id = getPrefix() . 'TMPL' . '-' . date('y') . '-' . str_pad($model->id, 6, '0', STR_PAD_LEFT);
            $model->save();
        });
    }

    public function scopeFilter(Builder $query, Request $request)
    {
        return $query->when($request->get('factory_id'), Filter::apply('factory_id', $request->get('factory_id')))
            ->when($request->get('buyer_id'), Filter::apply('buyer_id', $request->get('buyer_id')))
            ->when($request->get('lead_time'), Filter::apply('lead_time', $request->get('lead_time')))
            ->when($request->get('number_of_task'), Filter::apply('number_of_task', $request->get('number_of_task')))
            ->when($request->get('tna_for'), Filter::apply('tna_for', $request->get('tna_for')));
    }
}
