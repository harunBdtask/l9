<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Commercial\Models\Exports\CommercialRealization;
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class AccountRealization extends Model
{
    use SoftDeletes;

    protected $table = 'account_realizations';

    protected $fillable = [
        'realization_type_source',
        'factory_id',
        'bf_project_id',
        'bf_unit_id',
        'realization_type',
        'document_submission_id',
        'commercial_realization_id',
        'realization_number',
        'export_lc_id',
        'sales_contract_id',
        'export_invoice_id',
        'sc_number',
        'lc_number',
        'invoice_number',
        'realization_date',
        'realization_rate',
        'currency_id',
        'buyers',
        'styles',
        'po_numbers',
        'total_value',
        'realized_value',
        'short_realization',
        'foreign_bank_charge',
        'deduction',
        'total_deduction',
        'distribution',
        'loan_distribution',
        'total_distribution',
        'grand_total',
        'realized_gain_loss',
        'realized_difference',
        'approve_status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'export_lc_id' => Json::class,
        'sales_contract_id' => Json::class,
        'export_invoice_id' => Json::class,
        'sc_number' => Json::class,
        'lc_number' => Json::class,
        'invoice_number' => Json::class,
        'total_value' => Json::class,
        'realized_value' => Json::class,
        'short_realization' => Json::class,
        'foreign_bank_charge' => Json::class,
        'deduction' => Json::class,
        'total_deduction' => Json::class,
        'distribution' => Json::class,
        'loan_distribution' => Json::class,
        'total_distribution' => Json::class,
        'grand_total' => Json::class,
        'buyers' => Json::class,
        'styles' => Json::class,
        'po_numbers' => Json::class,
    ];

    protected $appends = [
        'realization_type_source_name',
        'realization_type_name',
        'realization_date_formatted',
        'total_value_fc_amount',
    ];

    public static function booted()
    {
        static::creating(function ($model) {
            $model->created_by = userId();
        });

        static::updating(function ($model) {
            $model->updated_by = userId();
        });

        static::saving(function ($model) {
            $model->factory_id = factoryId();
            if (!$model->id) {
                $model->created_by = userId();
            } else {
                $model->updated_by = userId();
            }
        });

        static::deleted(function ($model) {
            if (in_array('deleted_by', $model->getFillable())) {
                DB::table($model->table)
                    ->where('id', $model->id)
                    ->update([
                        'deleted_by' => userId(),
                    ]);
            }
        });
    }

    const REALIZATION_TYPE_SOURCES = [
        '1' => 'Manual',
        '2' => 'Auto',
    ];

    const REALIZATION_TYPES = [
        '1' => 'LDBC',
        '2' => 'FDBC',
        '3' => 'TT-Foreign',
        '4' => 'TT-Local',
    ];

    public function getRealizationTypeSourceNameAttribute()
    {
        return self::REALIZATION_TYPE_SOURCES[$this->attributes['realization_type_source']];
    }

    public function getRealizationTypeNameAttribute()
    {
        return self::REALIZATION_TYPES[$this->attributes['realization_type']];
    }

    public function getRealizationDateFormattedAttribute()
    {
        return $this->attributes['realization_date'] ? date('d/m/Y', \strtotime($this->attributes['realization_date'])) : null;
    }

    public function getTotalValueFcAmountAttribute()
    {
        $total_value = \json_decode($this->attributes['total_value'], true);
        
        return ($total_value && \is_array($total_value) && \array_key_exists('amount_usd', $total_value)) ? $total_value['amount_usd'] : null;
    }

    public function factory()
    {
        return $this->belongsTo(Factory::class, 'factory_id', 'id')->withDefault();
    }

    public function bfProject()
    {
        return $this->belongsTo(Project::class, 'bf_project_id', 'id')->withDefault();
    }

    public function bfUnit()
    {
        return $this->belongsTo(Unit::class, 'bf_unit_id', 'id')->withDefault();
    }

    public function documentSubmission()
    {
        return $this->belongsTo(DocumentSubmission::class, 'document_submission_id', 'id')->withDefault();
    }

    public function commercialRealization()
    {
        return $this->belongsTo(CommercialRealization::class, 'commercial_realization_id', 'id')->withDefault();
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'id')->withDefault();
    }
}
