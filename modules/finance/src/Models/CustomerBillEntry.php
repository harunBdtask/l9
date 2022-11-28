<?php

namespace SkylarkSoft\GoRMG\Finance\Models;

use App\Casts\Json;
use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Finance\Services\CurrencyService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Company;
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class CustomerBillEntry extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $fillable = [
        'group_id',
        'company_id',
        'project_id',
        'currency_id',
        'customer_id',
        'bill_basis',
        'bill_date',
        'bill_no',
        'details',
        'gin_no',
        'gin_date',
        'cons_rate',
        'discount',
        'fc_discount',
        'remarks',
        'gate_pass_no',
        'vehicle_no',
        'driver_name',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'details' => Json::class,
    ];

    protected $appends = [
        'bill_basis_name',
        'currency_name',
    ];

    const BILL_BASIS = [
        '1' => 'INDEPENDENT',
        '2' => 'GIN',
    ];

    public static function booted()
    {
        static::created(function ($model) {
            $generate = str_pad($model->id, 5, "0", STR_PAD_LEFT);
            $model->bill_no = 'IV-' . $generate;
            $model->save();
        });
    }

    public function getBillBasisNameAttribute(): string
    {
        return isset($this->attributes['bill_basis']) ?
            array_key_exists($this->attributes['bill_basis'], self::BILL_BASIS) ? self::BILL_BASIS[$this->attributes['bill_basis']] : ''
            : '';
    }

    public function getCurrencyNameAttribute(): string
    {
        return isset($this->attributes['currency_id']) ?
            collect(CurrencyService::currencies())->where('id', $this->attributes['currency_id'])->first()['name'] ?? ''
            : '';
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'group_id')->withDefault();
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'company_id')->withDefault();
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id')->withDefault();
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'customer_id')->withDefault();
    }
}
