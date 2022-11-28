<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplicationMenuActiveData extends Model
{
    use SoftDeletes;

    protected $table = 'application_menu_active_data';

    protected $fillable = [
        'inactive_urls',
        'inactive_menus',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'inactive_urls' => Json::class,
        'inactive_menus' => Json::class,
    ];

    public static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            if (!$model->id) {
                $model->created_by = \userId();
            }
            $model->updated_by = \userId();
        });
        static::creating(function ($post) {
            $post->created_by = \userId();
        });
        static::updating(function ($post) {
            $post->updated_by = \userId();
        });
        static::deleting(function ($post) {
            $post->deleted_by = \userId();
        });
    }

    public function createdUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }

    public function updatedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by')->withDefault();
    }

    public function deletedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by')->withDefault();
    }
}
