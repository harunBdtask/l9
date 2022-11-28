<?php

namespace SkylarkSoft\GoRMG\TrimsStore\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

trait CommonBooted
{
    /**
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, "created_by", "id")
            ->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, "updated_by", "id")
            ->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, "deleted_by", "id")
            ->withDefault();
    }

    /**
     * created_by , updated_by , deleted_by column event
     */
    public static function bootCommonBooted()
    {
        static::creating(function ($model) {
            $model->created_by = Auth::id();
        });

        static::saving(function ($model) {
            $model->updated_by = Auth::id();
        });

        static::deleted(function ($model) {
            if (in_array('deleted_by', $model->getFillable())) {
                DB::table($model->table)->where('id', $model->id)
                    ->update([
                        'deleted_by' => Auth::id(),
                    ]);
            }
        });
    }
}
