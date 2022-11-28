<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Knitting\Traits\CommonBooted;

class CareLabelType extends Model
{
    use SoftDeletes;
    use CommonBooted;

    protected $table = 'care_label_types';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
