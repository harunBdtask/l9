<?php

namespace SkylarkSoft\GoRMG\Knitting\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Knitting\Models\KnitProgramRoll;
use SkylarkSoft\GoRMG\SystemSettings\Models\Size;

class KnittingProgramCollarCuffProduction extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $table = 'knitting_program_collar_cuff_productions';

    protected $fillable = [
        'knitting_program_id',
        'knitting_program_collar_cuff_id',
        'knitting_program_roll_id',
        'gmt_color_id',
        'gmt_color',
        'size_id',
        'size',
        'program_item_size',
        'program_qty',
        'production_qty',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $dates = ['deleted_at'];
    protected $appends = ['balance_qty'];

    public function getBalanceQtyAttribute()
    {
        return $this->program_qty - $this->production_qty;
    }

    public function knittingCollarCuff(): BelongsTo
    {
        return $this->belongsTo(
            KnittingProgramCollarCuff::class,
            'knitting_program_collar_cuff_id',
            'id')->withDefault();
    }

    public function knittingProgram()
    {
        return $this->belongsTo(KnittingProgram::class, 'knitting_program_id', 'id')->withDefault();
    }

    public function knittingProgramRoll()
    {
        return $this->belongsTo(KnitProgramRoll::class, 'knitting_program_roll_id', 'id')->withDefault();
    }

    public function garmentsColor()
    {
        return $this->belongsTo(Color::class, 'gmt_color_id', 'id')->withDefault();
    }

    public function size()
    {
        return $this->belongsTo(Size::class, 'size_id', 'id')->withDefault();
    }
}
