<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models;

use App\FactoryIdTrait;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\BuyingAgentModel;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class Sample extends Model
{
    use FactoryIdTrait;
    use SoftDeletes;
    use CascadeSoftDeletes;

    protected $table = 'samples';
    protected $fillable = [
        'buyer_id',
        'agent_id',
        'sample_ref_no',
        'receive_date',
        'sample_image',
        'remarks',
        'team_leader',
        'dealing_merchant',
        'season',
        'currency',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    protected $dates = ['deleted_at', 'receive_date'];
    protected $cascadeDeletes = ['sampleDetails'];

    public function buyer()
    {
        return $this->belongsTo(Buyer::class, 'buyer_id', 'id');
    }

    public function agent()
    {
        return $this->belongsTo(BuyingAgentModel::class, 'agent_id', 'id');
    }

    public function sampleDetails()
    {
        return $this->hasMany(SampleDetail::class, 'sample_id', 'id');
    }

    public function approvalStatus()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Merchandising\Models\Status', 'approval_status', 'id');
    }

    public function status()
    {
        return $this->belongsTo(SampleList::class, 'sample_status');
    }

    public function quotationMaster()
    {
        return $this->hasOne('SkylarkSoft\GoRMG\Merchandising\Models\QuotationMaster', 'sample_development_id', 'id');
    }

    public function dealingMerchant()
    {
        return $this->belongsTo(User::class, 'dealing_merchant', 'id');
    }

    public function teamLead()
    {
        return $this->belongsTo(User::class, 'team_leader', 'id');
    }
}
