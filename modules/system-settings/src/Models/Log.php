<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = "logs";
    protected $primaryKey = "id";
    protected $fillable = ["message", "code", "url", "method", "meta"];
}
