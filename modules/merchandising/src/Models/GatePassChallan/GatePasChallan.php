<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models\GatePassChallan;


use App\Casts\Json;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\Merchandising\Filters\Filter;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\ProductDepartments;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class GatePasChallan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mer_gate_pass_challans';

    protected $fillable = [
        'challan_no',
        'challan_date',
        'department_id',
        'factory_id',
        'merchant_id',
        'supplier_id',
        'good_id',
        'status',
        'remarks',
        'file',
        'goods_details',
        'vehicle_no',
        'driver_name',
        'lock_no',
        'bag_quantity',
        'ready_to_approve',
        'unapprove_request',
        'created_by',
        'updated_by',
        'deleted_by',
        'gp_exit_point_scanned_by',
        'gp_exit_point_scanned_at',
        'returnable',
        'party_attn',
        'party_contact_no',
        'supplier_email_address',
        'supplier_address',
    ];

    protected $appends = ['barcode'];

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->created_by = Auth::id();
        });

        static::created(function ($model) {
            $model->challan_no =  getPrefix() . 'GPC-' . date('y') . '-' . str_pad($model->id, 6, '0', STR_PAD_LEFT);
            $model->save();
        });

        self::updating(function ($model) {
            $model->updated_by = Auth::id();
        });

        self::deleting(function ($model) {
            $model->deleted_by = Auth::id();
        });
    }

    protected $casts = [
        'goods_details' => Json::class,
    ];


    const GOODS = [1 => 'Sample', 2 => 'Fabric', 3 => 'Trims', 4 => 'Yarn'];
    const STATUS = [1 => 'Development', 2 => 'Confirm Order'];


    public function merchant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'merchant_id')->withDefault();
    }

    public function party(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id')->withDefault();
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(ProductDepartments::class, 'department_id')->withDefault();
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id')->withDefault();
    }

    public function scopeApprovalFilter($query, $request, $previousStep, $step)
    {
        return $query->when($request->get('factory_id'), Filter::applyFilter('factory_id', $request->get('factory_id')))
            ->when($request->get('supplier_id'), Filter::applyFilter('supplier_id', $request->get('supplier_id')))
            ->when($request->get('department_id'), Filter::applyFilter('department_id', $request->get('department_id')))
            ->when($request->get('good_id'), Filter::applyFilter('good_id', $request->get('good_id')))
            ->when($request->get('challan_date'), Filter::applyFilter('challan_date', $request->get('challan_date')))
            ->when($request->get('challan_no'), Filter::applyFilter('challan_no', $request->get('challan_no')))
            ->when($request->get('approvalType'), function ($query) use ($request, $step) {
                $query->when($request->get('approvalType') == 1, function ($query) use ($request, $step) {
                    $query->where('ready_to_approve', $request->get('approvalType'))
                        ->where('is_approve', '=', null)
                        ->where('step', $step - 1);
                })->when($request->get('approvalType') == 2, function ($query) use ($step) {
                    $query->where('step', $step);
                });
            });
    }

    public function getBarcodeAttribute(): string
    {
        return str_pad($this->attributes['id'], 10, '0', STR_PAD_LEFT);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
