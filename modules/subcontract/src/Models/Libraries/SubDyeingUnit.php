<?php

namespace SkylarkSoft\GoRMG\Subcontract\Models\Libraries;

use App\Contracts\AuditAbleContract;
use App\Models\BelongsToFactory;
use App\Traits\AuditAble;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class SubDyeingUnit extends Model implements AuditAbleContract
{
    use SoftDeletes;
    use BelongsToFactory;
    use AuditAble;

    protected $table = 'sub_dyeing_units';
    protected $primaryKey = 'id';
    protected $fillable = [
        'factory_id',
        'name',
        'address',
        'contact_no',
        'attention',
        'email',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected static function booted()
    {
        self::creating(function ($model) {
            $model->created_by = Auth::id();
        });

        self::updating(function ($model) {
            $model->updated_by = Auth::id();
        });

        self::deleting(function ($model) {
            $model->deleted_by = Auth::id();
        });
    }

    public function moduleName(): string
    {
        return 'sub-contract-dyeing';
    }

    public function path(): string
    {
        return url("subcontract/sub-dyeing-unit/$this->id/edit");
    }
}
