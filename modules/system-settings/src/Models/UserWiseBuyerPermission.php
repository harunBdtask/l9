<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserWiseBuyerPermission extends Model
{
    use HasFactory;

    protected $fillable = ['factory_id', 'user_id', 'buyer_id','view_buyer_id'];

    const BUYER_PERMISSION = 1;
    const VIEW_BUYER_PERMISSION = 2;

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id')->withDefault();
    }

    public function viewBuyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'view_buyer_id','id')->withDefault();
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id')->withDefault();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }

    public function scopeSearch($query, $search)
    {
        return $query->when($search, function ($query) use ($search) {
            $query->whereHas('user', function ($query) use ($search) {
                $query->where('screen_name', 'like', "%{$search}%");
            });
        });
    }
}
