<?php

namespace SkylarkSoft\GoRMG\Commercial\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class ActualCost extends Model
{
    use HasFactory;

    protected $table = "actual_costs";

    protected $fillable = [
        'company_id',
        'cost_head_id',
        'incurred_date_from',
        'incurred_date_to',
        'applying_period_from',
        'applying_period_to',
        'amount',
        'based_on',
    ];

    const COST_HEAD = [
        ['id' => 1, 'text' => 'Testing Cost'],
        ['id' => 2, 'text' => 'Freight Cost'],
        ['id' => 3, 'text' => 'Inspection Cost'],
        ['id' => 4, 'text' => 'Courier Cost'],
        ['id' => 5, 'text' => 'CM'],
        ['id' => 6, 'text' => 'Commercial'],
    ];
    const BASED_ON = [
        ['id' => 1, 'text' => 'Ex-factory Qty'],
        ['id' => 2, 'text' => 'Production Qty'],
    ];

    public function company()
    {
        return $this->belongsTo(Factory::class, 'company_id');
    }
}
