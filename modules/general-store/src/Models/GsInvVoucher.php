<?php

namespace SkylarkSoft\GoRMG\GeneralStore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\GeneralStore\Traits\CommonBooted;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;


class GsInvVoucher extends Model
{
    use HasFactory, SoftDeletes, CommonBooted;

    protected $table = 'gs_inv_vouchers';

    protected $fillable = [
        'type',
        'trn_date',
        'details', // json
        'trn_with',
        'trn_customer',
        'store',
        'requisition_s_code',
        'reference',
        'factory_id',
        'rack_id',
        'voucher_no',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'readonly' => 'boolean'
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->factory_id = factoryId();
        });

        static::created(function ($model) {
            $model->voucher_no = sprintf('%05d', $model->id);
            $model->save();

            GsInvBarcode::whereIn(
                'id',
                collect($model->details)->pluck('id')->toArray()
            )->update(['status' => false]);

        });

        static::updated(function ($model) {
            GsInvBarcode::whereIn(
                'id',
                collect($model->details)->pluck('id')->toArray()
            )->update(['status' => false]);
        });

        static::deleted(function ($model) {
            GsInvBarcode::whereIn(
                'id',
                collect($model->details)->pluck('id')->toArray()
            )->update(['status' => true]);
        });
    }


    /*
     * METHODS
     */
    public function makeReadonly()
    {
        $this->setAttribute('readonly', true);
        $this->save();
    }

    /*
     * RELATIONS
     */
    public function consumer()
    {
        return $this->belongsTo(User::class, 'trn_with');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'trn_with');
    }

    public function transactions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(GsInvTransaction::class);
    }

    /*
     * ACCESSORS - MUTATORS
     */
    public function setDetailsAttribute($details)
    {
        $this->attributes['details'] = json_encode($details);
    }

    public function getDetailsAttribute()
    {
        return json_decode($this->attributes['details']);
    }

    /*public function getStoreNameAttribute()
    {
        return get_store_name($this->attributes['store']);
    }*/


    /*
     * LOCAL SCOPES
     */
    public function scopeType(Builder $query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeStore(Builder $query, $store)
    {
        return $query->where('store', $store);
    }
}
