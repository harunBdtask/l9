<?php

namespace SkylarkSoft\GoRMG\Subcontract\Services;

use Illuminate\Support\Facades\Notification;
use SkylarkSoft\GoRMG\Subcontract\Notifications\BatchEntryNotification;
use SkylarkSoft\GoRMG\SystemSettings\Services\GroupNotificationUserService;

class BatchNotifyService
{
    private $data;
    private $type;

    /**
     * @param mixed $data
     */
    public function setData($data): BatchNotifyService
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): BatchNotifyService
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
            ->setType('dyeing_process_batch_entry')
            ->get();

        $notificationData = [
            'type' => $this->type,
            'id' => $this->data->id,
            'order_nos' => collect($this->data->order_nos)->join(', '),
            'batch_no' => $this->data->batch_no,
            'batch_weight' => $this->data->total_batch_weight,
            'changes' => collect($this->data->getChanges())->except(['updated_at', 'sub_textile_order_ids'])->keys()->join(', '),
        ];

        Notification::send($receivers, new BatchEntryNotification($notificationData));
    }
}
