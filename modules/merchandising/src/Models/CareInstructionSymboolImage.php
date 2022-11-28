<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CareInstructionSymboolImage extends Model
{
    use SoftDeletes;

    protected $table = 'care_instruction_symbool_images';

    protected $fillable = [
        'trims_accessory_detail_id',
        'image',
        'factory_id',
    ];

    protected $dates = [
        'deleted_at',
    ];

    public function trimsAccessoryDetails()
    {
        return $this->hasMany(TrimsAccessoryDetail::class);
    }
}
