<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\FactoryIdTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\Merchandising\QueryBuilders\CustomQuery;

class Team extends Model
{
    use FactoryIdTrait;
    use SoftDeletes;

    protected $table = 'teams';
    protected $guarded = [];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($team) {
            $team->factory_id = Auth::user()->factory_id;
            $team->created_by = Auth::id();
        });
        static::updating(function ($team) {
            $team->updated_by = Auth::id();
        });
    }

    public function teamWiseFactories(): HasMany
    {
        return $this->hasMany(TeamWiseFactory::class, 'team_id');
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(
            Factory::class,
            'factory_id',
            'id'
        );
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(User::class, 'member_id')->withDefault(
            [
                'id' => 'N\A'
            ]
        );
    }

    public function teamMemberAssign()
    {
        return $this->hasMany(TeamMemberAssign::class, 'team_id', 'id');
    }

    public function preparedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by',
            'id'
        );
    }

    public function edittedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'updated_by',
            'id'
        );
    }

    public function deletedByUser(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'deleted_by',
            'id'
        );
    }

    public function scopeSearch($query, $searchKey)
    {
        return $query->when($searchKey, function ($query) use ($searchKey) {
            $query->where('team_name', 'LIKE', "%$searchKey%")
                ->orWhere('short_name', 'LIKE', "%$searchKey%")
                ->orWhere('project_type', 'LIKE', "%$searchKey%")
                ->orWhere('role', 'LIKE', "%$searchKey%");
        });
    }

    public function newEloquentBuilder($query): CustomQuery
    {
        return new CustomQuery($query);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'member_id');
    }
}
