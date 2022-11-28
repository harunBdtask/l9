<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BodyPart extends Model
{
    use SoftDeletes;
    protected $table = 'body_parts';
    protected $guarded = [];
}
