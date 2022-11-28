<?php

namespace SkylarkSoft\GoRMG\Subcontract\Services;

use Illuminate\Support\Facades\Notification;
use SkylarkSoft\GoRMG\Subcontract\Notifications\SubGoodsDeliveryNotification;
use SkylarkSoft\GoRMG\SystemSettings\Services\GroupNotificationUserService;

class SubGoodsDeliveryNotifyService
{
    private $data;
    private $type;

    /**
     * @param mixed $data
     */
    public function setData($data): SubGoodsDeliveryNotifyService
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): SubGoodsDeliveryNotifyService
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return void
     */
    public function notify()
    {
        $receivers = (new GroupNotificationUserService())
            ->setType('sub_goods_delivery')
            ->get();

        $batchOrOrderNos = $this->data->batch_no ?? $this->data->order_no ?? [];
        $allChanges = '';

        if ($this->type == 'updated') {
            $mainPartChanges = collect($this->data->getChanges())->except(['updated_at', 'order_id', 'order_no'])->keys();
            $allChanges = $mainPartChanges->merge($this->data['change_details'])->unique()->values()->join(', ');
        }

        $notificationData = [
            'type' => $this->type,
            'id' => $this->data->id,
            'goods_delivery_uid' => $this->data->goods_delivery_uid,
            'batch_order_no' => collect($batchOrOrderNos)->join(', '),
            'entry_basis_value' => $this->data->entry_basis_value,
            'changes' => collect(explode(', ', $allChanges))->unique()->values()->join(', '),
        ];

        Notification::send($receivers, new SubGoodsDeliveryNotification($notificationData));
    }
}
