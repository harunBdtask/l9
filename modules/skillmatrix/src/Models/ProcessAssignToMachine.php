<?php

namespace SkylarkSoft\GoRMG\Skillmatrix\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProcessAssignToMachine extends Model
{
    use SoftDeletes;
    use FactoryIdTrait;

    protected $table = 'process_assign_to_machines';

    protected $fillable = [
        'sewing_machine_id',
        'sewing_process_id',
        'created_by',
        'updated_by',
        'deleted_by',
        'factory_id',
    ];

    protected $dates = ['deleted_at'];

    public function sewingProcess(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SewingProcess::class, 'sewing_process_id', 'id')->withDefault();
    }

    public function sewingMachine(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SewingMachine::class, 'sewing_machine_id', 'id')->withDefault();
    }
}
