<?php

namespace SkylarkSoft\GoRMG\McInventory\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use SkylarkSoft\GoRMG\McInventory\Models\MachineLocation;

class McMachineTransfer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "mc_machine_transfers";

    protected $fillable = [
        'machine_id',
        'transfer_from',
        'transfer_to',
        'reason',
        'attention',
        'contact_no'
    ];

    public function machine()
    {
        return $this->belongsTo(McMachine::class,'machine_id','id')->withDefault();
    }

    public function machineTransferFrom()
    {
        return $this->belongsTo(MachineLocation::class,'transfer_from','id')->withDefault();
    }

    public function machineTransferTo()
    {
        return $this->belongsTo(MachineLocation::class,'transfer_to','id')->withDefault();
    }
}
