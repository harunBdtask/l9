<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;

class SubcontractDetail extends Model
{
    use SoftDeletes;
    protected $table = 'subcontract_details';
    protected $fillable = [
        'id',
        'subcontract_master_id',
        'color_id',
        'fabric_qty',
        'cutting_qty',
        'print_qty',
        'output_qty',
        'input_qty',
        'finishing_qty',
        'rejection_qty',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public function subcontract_master()
    {
        return $this->belongsTo(SubcontractMaster::class, 'subcontract_master_id', 'id');
    }

    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id', 'id');
    }
}
