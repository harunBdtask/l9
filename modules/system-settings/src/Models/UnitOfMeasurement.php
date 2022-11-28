<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\FactoryIdTrait;
use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class UnitOfMeasurement extends Model
{
    use SoftDeletes;
    use ModelCommonTrait;

    protected $table = 'unit_of_measurements';

    protected $primaryKey = 'id';

    protected $guarded = [];

    protected $dates = ['deleted_at'];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($post) {
            $post->factory_id = Auth::user()->factory_id;
            $post->created_by = Auth::id();
        });
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id', 'id');
    }

    public function scopeIsActive($query)
    {
        return $query->where('status', 'Active');
    }

}
