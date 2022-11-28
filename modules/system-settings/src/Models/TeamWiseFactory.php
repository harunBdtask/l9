<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeamWiseFactory extends Model
{
    use SoftDeletes;

    protected $table = 'team_wise_factories';
    protected $primaryKey = 'id';
    protected $fillable = [
        'team_id',
        'factory_id'
    ];

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id')->withDefault();
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_id')->withDefault();
    }
}
