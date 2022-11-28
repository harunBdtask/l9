<?php

namespace SkylarkSoft\GoRMG\Subcontract\Models\Libraries;

use App\Casts\Json;
use App\Models\BelongsToFactory;
use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubcontractVariableSetting extends Model
{
    use SoftDeletes;
    use CommonModelTrait;
    use BelongsToFactory;

    protected $table = 'subcontract_variable_settings';
    protected $primaryKey = 'id';
    protected $fillable = [
        'factory_id',
        'variable_name', // Json
        'variable_details', // Json
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'variable_name' => Json::class,
        'variable_details' => Json::class,
    ];

    const RECEIVE_BASIS = 'receive_basis';
    const ISSUE_BASIS = 'issue_basis';

    const BATCH_CREATION = [
        'receive_basis' => 'Receive Basis',
        'issue_basis' => 'Issue Basis',
    ];
}
