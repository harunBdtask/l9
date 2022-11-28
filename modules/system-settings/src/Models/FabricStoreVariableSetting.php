<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Merchandising\QueryBuilders\CustomQuery;

class FabricStoreVariableSetting extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $table = 'fabric_store_variable_settings';
    protected $primaryKey = 'id';
    protected $fillable = [
        'factory_id',
        'barcode',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * @param $query
     * @return CustomQuery
     */
    public function newEloquentBuilder($query): CustomQuery
    {
        return new CustomQuery($query);
    }
}
