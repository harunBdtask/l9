<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrintFactoryTable extends Model
{
    use FactoryIdTrait;
    use SoftDeletes;
    protected $table = 'print_factory_tables';
    protected $fillable = [
        'name',
        'facotory_id',
    ];
}
