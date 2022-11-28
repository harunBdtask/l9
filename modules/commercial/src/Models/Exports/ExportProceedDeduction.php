<?php


namespace SkylarkSoft\GoRMG\Commercial\Models\Exports;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Commercial\Constants\CommercialConstant;
use SkylarkSoft\GoRMG\Commercial\Models\AccountHead;

class ExportProceedDeduction extends Model
{
    use SoftDeletes;

    protected $table = 'export_proceed_details';

    protected $fillable = [
        'export_proceed_realization_id',
        'account_head_id',
        'document_currency',
        'conversion_rate',
        'domestic_currency',
        'status',
    ];
    protected $dates = ['deleted_at'];

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('deductionStatus', function (Builder $builder) {
            $table = $builder->getModel()->getTable();
            $builder->where(($table ? $table.'.' : '').'status', CommercialConstant::ExportProceedDeductionStatus);
        });

        static::created(function ($model) {
            $model->status = CommercialConstant::ExportProceedDeductionStatus;
        });

        static::saving(function ($model) {
            $model->status = CommercialConstant::ExportProceedDeductionStatus;
        });
    }

    public function exportProceedRealization()
    {
        return $this->belongsTo(ExportProceedsRealization::class, 'export_proceed_realization_id')->withDefault();
    }

    public function accountHead()
    {
        return $this->belongsTo(AccountHead::class, 'account_head_id')->withDefault();
    }
}
