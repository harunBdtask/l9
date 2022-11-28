<?php

namespace SkylarkSoft\GoRMG\Subcontract\Models\Libraries;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class SubTextileOperation extends Model
{
    use SoftDeletes;
    use ModelCommonTrait;

    protected $table = 'sub_textile_operations';

    protected $fillable = [
        'name',
        'material_popup_status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id')->withDefault();
    }

    public function createdUser(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'created_by')->withDefault();
    }

    public function updatedUser(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'updated_by')->withDefault();
    }

    public function deletedUser(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'deleted_by')->withDefault();
    }

    public function subTextileProcesses(): HasMany
    {
        return $this->hasMany(SubTextileProcess::class, 'sub_textile_operation_id', 'id');
    }
}
