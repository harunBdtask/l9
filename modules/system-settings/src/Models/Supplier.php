<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\Merchandising\QueryBuilders\CustomQuery;

class Supplier extends Model
{
    use FactoryIdTrait;
    use SoftDeletes;

    protected $guarded = [];

    protected $dates = ['deleted_at'];

    const ACCESSORIES = "Accessories Supplier";
    const TRIMS = "Trims Supplier";
    const LOAN_PARTY = 'Loan Party';

    public static function boot()
    {
        parent::boot();
        static::creating(function ($post) {
            $post->factory_id = Auth::user()->factory_id;
            $post->created_by = Auth::id();
        });
    }

    public function supplierWiseFactories(): HasMany
    {
        return $this->hasMany(SupplierWiseFactory::class, 'supplier_id');
    }

    public function party(): BelongsTo
    {
        return $this->belongsTo(Party::class);
    }

    public function factory()
    {
        return $this->belongsTo(Factory::class, 'factory_id', 'id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function partytype()
    {
        return $this->belongsTo(PartyType::class, 'party_type_ids', 'id');
    }

    public static function getSuppliers()
    {
        return self::all();
    }

    public function newEloquentBuilder($query): CustomQuery
    {
        return new CustomQuery($query);
    }
}
