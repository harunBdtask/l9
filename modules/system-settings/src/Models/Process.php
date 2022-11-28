<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Process extends Model
{
    use SoftDeletes;
    protected $table = 'process';
    protected $primaryKey = 'id';
    protected $fillable = [
        'process_name', 'color_wise_charge_unit',
    ];
}
