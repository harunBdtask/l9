<?php

namespace SkylarkSoft\GoRMG\DyesStore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\Commercial\Models\ProformaInvoice;
use SkylarkSoft\GoRMG\DyesStore\Traits\CommonBooted;
use SkylarkSoft\GoRMG\Inventory\Filters\Filter;
use SkylarkSoft\GoRMG\Settings\Models\StorageLocation;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;

class DyesChemicalsReceive extends Model
{
    use HasFactory, SoftDeletes, CommonBooted;

    /**
     * @var string
     */
    protected $table = 'dyes_chemicals_receive';

    /**
     * @var string
     */
    protected $primary_key = 'id';

    /**
     * @var string[]
     */
    protected $fillable = [
        'system_generate_id',
        'receive_basis',
        'receive_basis_id',
        'supplier_id',
        'receive_date',
        'reference_no',
        'bill_no',
        'storage_location_id',
        'lc_no',
        'lc_receive_date',
        'readonly',
        'details', // Json
        'is_approve',
        'ready_to_approve',
        'un_approve_request',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'readonly' => 'boolean'
    ];

    public function setSystemGenerateIdAttribute()
    {
        $this->attributes['system_generate_id'] = 'DCR-' . time();
    }

    /**
     * @param $value
     */
    public function setDetailsAttribute($value)
    {
        $this->attributes['details'] = json_encode($value);
    }

    /**
     * @param $value
     * @return mixed
     */
    public function getDetailsAttribute($value)
    {
        return json_decode($value, true);
    }

    /**
     * @param $query
     * @param $search
     * @return mixed
     */
    public function scopeFilter($query, $search)
    {
        return $query->when($search, function ($query) use ($search) {
            $query->where('lc_no', 'LIKE', '%' . $search . '%')
                ->orWhere('reference_no', 'LIKE', '%' . $search . '%')
                ->orWhereHas('supplier', function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search . '%');
                })
                ->orWhereHas('storageLocation', function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search . '%');
                });
        });
    }

    /**
     * @return BelongsTo
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id')->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function storageLocation(): BelongsTo
    {
        return $this->belongsTo(DsStoreModel::class, 'storage_location_id')->withDefault();
    }


    public function proformaInvoice(): BelongsTo
    {
        return $this->belongsTo(ProformaInvoice::class, 'receive_basis_id')->withDefault();
    }

    /**
     * Boot Function
     */
    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->created_by = Auth::user()->id;
        });

        static::updated(function ($model) {
            $model->updated_by = Auth::user()->id;
        });

        static::deleted(function ($model) {
            $model->deleted_by = Auth::user()->id;
        });
    }

    public function scopeApprovalFilter($query, $request, $previousStep, $step)
    {
        return $query->when($request->get('supplier_id'), Filter::applyFilter('supplier_id', $request->get('supplier_id')))
            ->when($request->get('receive_no'), Filter::applyFilter('system_generate_id', $request->get('receive_no')))
            ->when($request->get('reference_no'), Filter::applyFilter('reference_no', $request->get('reference_no')))
            ->when($request->get('receive_date'), Filter::applyFilter('receive_date', $request->get('receive_date')))
            ->when($request->get('lc_no'), Filter::applyFilter('lc_no', $request->get('lc_no')))
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
}
