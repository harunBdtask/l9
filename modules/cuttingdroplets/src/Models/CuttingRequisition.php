<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\FactoryIdTrait;

class CuttingRequisition extends Model
{
    use FactoryIdTrait, SoftDeletes;

    protected $table = 'cutting_requisitions';
    protected $fillable = [
    	'cutting_requisition_no',    	
    	'approval_status',
    	'created_by',
    	'updated_by',
    	'deleted_by',
    	'factory_id'
    ];

    protected $dates = ['deleted_at'];

    public function cuttingRequisitionDetails()
    {
        return $this->hasMany('SkylarkSoft\GoRMG\Cuttingdroplets\Models\CuttingRequisitionDetail', 'cutting_requisition_id');
    }
}
