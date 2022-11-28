<?php

namespace SkylarkSoft\GoRMG\Knitting\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class KnittingProgramCollarCuff extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $table = 'knitting_program_collar_cuffs';

    protected $primaryKey = 'id';

    protected $fillable = [
        'knitting_program_id',
        'booking_no',
        'gmt_color_id',
        'gmt_color',
        'size_id',
        'size',
        'booking_item_size',
        'program_item_size',
        'booking_qty',
        'excess_percentage',
        'program_qty',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function knittingProgram(): BelongsTo
    {
        return $this->belongsTo(KnittingProgram::class, 'knitting_program_id', 'id')->withDefault();
    }
}
