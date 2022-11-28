<?php

namespace SkylarkSoft\GoRMG\Dyeing\Database\Factories;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\TextileOrders\TextileOrder;
use SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\UId\TextileOrderService;

class TextileOrderFactory extends Factory
{
    protected $model = TextileOrder::class;

    public function definition(): array
    {
        return [
            'unique_id' => TextileOrderService::generateUniqueId(),
            'factory_id' => factoryId(),
            'buyer_id' => 6,
            'fabric_sales_order_id' => 1,
            'fabric_sales_order_no' => 'PN-FSOE-22-000001',
            'description' => null,
            'receive_date' => Carbon::now()->format('Y-m-d'),
            'currency_id' => 1,
            'payment_basis' => 1,
            'created_by' => Auth::user()->id,
            'updated_by' => null,
            'deleted_by' => null,
        ];
    }
}
