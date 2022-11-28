<?php

namespace SkylarkSoft\GoRMG\Finance\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class Unit extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fi_units';

    protected $fillable = [
        'factory_id',
        'fi_project_id',
        'user_ids',
        'unit',
        'unit_head_name',
        'phone_no',
        'email',
    ];
    protected $casts = [
        'user_ids' => Json::class,
    ];
    protected $dates = ['deleted_at'];

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id')->withDefault();
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'fi_project_id')->withDefault();
    }
}
