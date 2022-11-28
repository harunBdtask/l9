<?php

namespace SkylarkSoft\GoRMG\Finance\Models;

use App\Casts\Json;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\Finance\Models\Unit;

class Project extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected $table = 'fi_projects';

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
        return $this->hasMany(Unit::class, 'fi_project_id', 'id');
    }
}
