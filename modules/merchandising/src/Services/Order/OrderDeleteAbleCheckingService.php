<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\Order;

use SkylarkSoft\GoRMG\Merchandising\Models\Order;

class OrderDeleteAbleCheckingService
{
    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function action(): array
    {
        $data = [
            'status' => true,
            'msg' => 'Data Deleted Successfully',
            'flash_status' => 'success'
        ];
        $bundleExist = $this->hasBundleCards();
        if ($bundleExist) {
            $data['status'] = false;
            $data['msg'] = 'Cannot delete because Bundle Card already exists in Cutting!';
            $data['flash_status'] = 'error';
        }
        return $data;
    }

    private function hasBundleCards()
    {
        return $this->order->bundleCards()->count();
    }
}