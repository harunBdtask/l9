<?php

namespace SkylarkSoft\GoRMG\Skillmatrix\Models;

use App\FactoryIdTrait;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Floor;
use SkylarkSoft\GoRMG\SystemSettings\Models\Line;

class SewingOperator extends Model
{
    use SoftDeletes;
    use FactoryIdTrait;
    use CascadeSoftDeletes;

    protected $table = 'sewing_operators';
    protected $fillable = [
        'name',
        'title',
        'operator_id',
        'operator_grade',
        'floor_id',
        'line_id',
        'present_salary',
        'joinning_date',
        'image',
        'created_by',
        'updated_by',
        'deleted_by',
        'factory_id',
    ];

    protected $dates = ['deleted_at'];
    protected $cascadeDeletes = ['sewingOperatorSkills'];

    const OPERATOR_SEARCH_COLUMNS = [
        'name' => 'Name',
        'title' => 'Title',
        'operator_id' => 'Operator ID',
        'operator_grade' => 'Operator Grade',
    ];

    public function floor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Floor::class, 'floor_id', 'id')->withDefault();
    }

    public function line(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Line::class, 'line_id', 'id')->withDefault();
    }

    public function sewingOperatorSkills(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SewingOperatorSkill::class, 'sewing_operator_id', 'id');
    }
}
