<?php

namespace SkylarkSoft\GoRMG\Printembrdroplets\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\PrintFactoryTable;
use App\FactoryIdTrait;

class PrintEmbrTarget extends Model
{
    use FactoryIdTrait, SoftDeletes;

    protected $table = 'print_embr_targets';

    protected $fillable = [
        'target_date',
        'print_factory_table_id',
        'man_power',
        'target_qty',
        'working_hour',
        'remarks',        
        'factory_id',
       	'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $dates = ['deleted_at'];

    public function print_factory_table()
    {
        return $this->belongsTo(PrintFactoryTable::class, 'print_factory_table_id');
    }
}
