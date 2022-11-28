<?php

namespace SkylarkSoft\GoRMG\Finance\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use SkylarkSoft\GoRMG\SystemSettings\Models\Company;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use SkylarkSoft\GoRMG\Finance\Models\SupplierBillPaymentBillNo;

class SupplierBillEntry extends Model
{
    use HasFactory;

    protected $table = 'supplier_bill_entries';
    protected $fillable = [
        'group_id',
        'company_id',
        'project_id',
        'entry_type',
        'bill_receive_date',
        'supplier_id',
        'bill_number',
        'bill_date',
        'pi_no',
        'pi_value',
        'po_number',
        'po_date',
        'currency_id',
        'con_rate',
        'discount_rate',
        'details',
        'vat_type',
        'vat_rate',
        'total_vat',
        'tds',
        'tds_rate',
        'total_tds',
        'party_payable',
        'job_number'
    ];

    public static $vatTypes = [
        0=> 'No',
        1=> 'Including',
        2=> 'Excluding'
    ];
    public static $tdsTypes = [
        0=> 'No',
        1=> 'Yes'
    ];
    public static $payModes = [
        1=> 'Cash',
        2=> 'Cheque',
        3=> 'LC',
    ];
    protected $casts = [
        'details' => Json::class,
    ];

    public function scopeSearch(Builder $query, $search)
    {
        $query->when($search, function (Builder $builder) use ($search) {
            $builder->whereDate('bill_receive_date', $search);
            $builder->orWhere('bill_number', 'LIKE', '%'. $search . '%');
        });
    }
    public function detailsList()
    {
        return $this->hasOne(SupplierBillEntry::class, 'id');
    }
    public function group()
    {
        return $this->belongsTo(Company::class, 'group_id','id');
    }

    public function company()
    {
        return $this->belongsTo(Factory::class, 'company_id','id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class,'supplier_id','id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
    public function billNos(): HasMany
    {
        return $this->hasMany(SupplierBillPaymentBillNo::class, 'bill_entry_id', 'id');
    }
}
