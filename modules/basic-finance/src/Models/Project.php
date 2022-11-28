<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Models;

use App\Casts\Json;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class Project extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected $table = 'bf_projects';

    protected $fillable = [
        'factory_id',
        'user_ids',
        'project',
        'project_head_name',
        'phone_no',
        'email',
    ];
    protected $casts = [
        'user_ids' => Json::class,
    ];
    protected $dates = ['deleted_at'];

    protected $cascadeDeletes = [ ];

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id')->withDefault();
    }

    public function units(): HasMany
    {
        return $this->hasMany(Unit::class, 'bf_project_id', 'id');
    }
}
