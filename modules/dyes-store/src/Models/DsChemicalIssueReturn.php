<?php

namespace SkylarkSoft\GoRMG\DyesStore\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\DyesStore\Services\DyesChemicalIssueReturnService;
use SkylarkSoft\GoRMG\DyesStore\Traits\CommonBooted;

class DsChemicalIssueReturn extends Model
{
    use SoftDeletes, CommonBooted;

    protected $table ="dyes_chemical_issue_return";
    protected $primaryKey = "id";
    protected $fillable = [
        'system_generate_id',
        'issue_id',
        'return_date',
        'readonly',
        'details',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'details' => Json::class
    ];

    public static function booted()
    {
        static::saving(function ($model) {
            if (! $model->id && in_array('created_by', $model->getFillable())) {
                $model->system_generate_id = DyesChemicalIssueReturnService::generateUniqueId();
            }
        });
    }
}
