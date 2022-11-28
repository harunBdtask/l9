<?php

namespace SkylarkSoft\GoRMG\Iedroplets\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\GuideOrFolder;
use SkylarkSoft\GoRMG\SystemSettings\Models\MachineType;
use SkylarkSoft\GoRMG\SystemSettings\Models\OperatorSkill;
use SkylarkSoft\GoRMG\SystemSettings\Models\Task;

class OperationBulletinDetail extends Model
{
    use SoftDeletes;
    use FactoryIdTrait;

    protected $table = 'operation_bulletin_details';

    protected $fillable = [
        'task_id',
        'special_task',
        'operator_skill_id',
        'machine_type_id',
        'special_machine',
        'guide_or_folder_id',
        'work_station',
        'time',
        'idle_time',
        'new_work_station',
        'new_time',
        'new_idle_time',
        'hourly_target',
        'remarks',
        'factory_id'
    ];

    public function task(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id')->withDefault();
    }

    public function operatorSkill(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(OperatorSkill::class, 'operator_skill_id')->withDefault();
    }

    public function machineType(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MachineType::class, 'machine_type_id')->withDefault();
    }

    public function guideOrFolder(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(GuideOrFolder::class, 'guide_or_folder_id')->withDefault();
    }
}
