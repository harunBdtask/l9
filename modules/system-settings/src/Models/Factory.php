<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\Casts\Json;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use SkylarkSoft\GoRMG\BasicFinance\Models\Project;
use SkylarkSoft\GoRMG\BasicFinance\Models\Unit;
use SkylarkSoft\GoRMG\Merchandising\QueryBuilders\CustomQuery;

class Factory extends Model
{
    use SoftDeletes;
    use CascadeSoftDeletes;

    protected $fillable = [
        'group_name',
        'factory_name',
        'factory_name_bn',
        'factory_short_name',
        'factory_address',
        'factory_address_bn',
        'responsible_person',
        'sewing_rejection_type',
        'phone_no',
        'factory_image',
        'associate_factories',
    ];

    protected $casts = [
        'associate_factories' => Json::class,
    ];

    protected $dates = ['deleted_at'];

    protected $cascadeDeletes = [];

    public function bundleCards(): HasMany
    {
        return $this->hasMany('SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard');
    }

    public function cuttingTargets(): HasMany
    {
        return $this->hasMany('SkylarkSoft\GoRMG\Iedroplets\Models\CuttingTarget');
    }

    public function teamMemberAssign(): HasMany
    {
        return $this->hasMany(TeamMemberAssign::class, 'factory_id', 'id');
    }

    public function knittingAllocations(): HasMany
    {
        return $this->hasMany('SkylarkSoft\GoRMG\Knittingdroplets\Models\KnittingAllocation', 'factory_id', 'id');
    }

    public function getFactoryCodeAttribute(): string
    {
        $factory_code = $this->factory_short_name ?? '';

        return "{$factory_code}";
    }

    public static function getFactories(): bool
    {
        $factories = Factory::pluck('factory_name', 'id')->all();
        Cache::put('factories', $factories, 43200);

        return true;
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'factory_id');
    }

    public function buyers(): HasMany
    {
        return $this->hasMany(Buyer::class, 'factory_id')->permittedBuyer();
    }

    public function garmentsProductionVariable()
    {
        return $this->hasOne(GarmentsProductionEntry::class, 'factory_id');
    }

    public function newEloquentBuilder($query): CustomQuery
    {
        return new CustomQuery($query);
    }

    public function lienBanks()
    {
        return $this->belongsToMany(LienBank::class, 'factory_lien_bank', 'factory_id', 'lien_bank_id')->withTimestamps();
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'factory_id', 'id');
    }

    public function units(): HasMany
    {
        return $this->hasMany(Unit::class, 'bf_project_id', 'id');
    }
}
