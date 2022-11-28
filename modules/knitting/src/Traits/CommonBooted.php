<?php


namespace SkylarkSoft\GoRMG\Knitting\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

trait CommonBooted
{
    /**
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, "created_by", "id")->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, "updated_by", "id")->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, "deleted_by", "id")->withDefault();
    }

    /**
     * created_by , updated_by , deleted_by column event
     */
    public static function booted()
    {
        static::saving(function ($model) {
            $model->created_by = auth()->user()->id;
            $model->updated_by = auth()->user()->id;
        });

        static::updating(function ($model) {
            $model->updated_by = auth()->user()->id;
        });

        static::deleting(function ($model) {
            $model->deleted_by = auth()->user()->id;
            $model->save();
        });
    }
}
