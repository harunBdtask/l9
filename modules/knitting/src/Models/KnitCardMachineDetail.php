<?php

namespace SkylarkSoft\GoRMG\Knitting\Models;

use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KnitCardMachineDetail extends Model
{
    use HasFactory, SoftDeletes, CommonModelTrait;

    protected $fillable = [
        'factory_id',
        'plan_info_id',
        'knitting_program_id',
        'knit_card_id',
        'machine_id',
        'priority',
        'production_remarks',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
