<?php

namespace SkylarkSoft\GoRMG\TimeAndAction\Services;

use Carbon\Carbon;
use SkylarkSoft\GoRMG\TimeAndAction\Models\TNATask;
use SkylarkSoft\GoRMG\TimeAndAction\Services\IntegrateWithPage\PageState;

class TNAReportTableDataFormatter
{
    public function formatForStyle($data): array
    {

        $detail = $data['details'];
        $order = $data['order'];
        $template = $data['template'];

        $startDate = Carbon::parse($order['po_received_date'])
            ->addDays($detail['start_from_day_no'] - 1)
            ->toDateString();

        $finishDate = Carbon::parse($startDate)
            ->addDays($detail['execution_days'] - 1)
            ->toDateString();

        $noticeBeforeDate = Carbon::parse($finishDate)
            ->subDays($detail['notice_before'])
            ->toDateString();

//        $pageType = TNATask::query()->where('id', $detail['task_id'])->first();

//        $actualDate = PageState::setState((int)$pageType->integration_with_entry_page)
//            ->setFactory($order->factory_id)
//            ->setBuyer($order->buyer_id)
//            ->setOrder($order->id)
//            ->get();

        return [
            'factory_id' => $order->factory_id,
            'buyer_id' => $order->buyer_id,
            'order_id' => $order->id,
            'based_on' => 2,
            'task_id' => $detail['task_id'],
            'lead_time' => $template['lead_time'],
            'execution_days' => $detail['execution_days'],
            'deadline' => $detail['deadline'],
            'notice_before' => $detail['notice_before'],
            'notice_before_date' => $noticeBeforeDate,
            'task_sequence' => $detail['task_sequence'],
            'finish_date' => $finishDate,
            'start_date' => $startDate,
        ];
    }

    public function formatForPO($data): array
    {

        $detail = $data['details'];
        $po = $data['po'];
        $template = $data['template'];

        $startDate = Carbon::parse($po['po_received_date'])
            ->addDays($detail['start_from_day_no'] - 1)
            ->toDateString();

        $finishDate = Carbon::parse($startDate)
            ->addDays($detail['execution_days'] - 1)
            ->toDateString();

        $noticeBeforeDate = Carbon::parse($finishDate)
            ->subDays($detail['notice_before'])
            ->toDateString();

//        $pageType = TNATask::query()->where('id', $detail['task_id'])->first();
//
//        $actualStartDate = PageState::setState((int)$pageType->integration_with_entry_page)
//            ->setFactory($order->factory_id)
//            ->setBuyer($order->buyer_id)
//            ->setOrder($order->id)
//            ->get();

        return [
            'factory_id' => $po->factory_id,
            'buyer_id' => $po->buyer_id,
            'order_id' => $po->order_id,
            'po_id' => $po->id,
            'based_on' => 1,
            'task_id' => $detail['task_id'],
            'lead_time' => $template['lead_time'],
            'execution_days' => $detail['execution_days'],
            'deadline' => $detail['deadline'],
            'notice_before' => $detail['notice_before'],
            'notice_before_date' => $noticeBeforeDate,
            'task_sequence' => $detail['task_sequence'],
            'finish_date' => $finishDate,
            'start_date' => $startDate,
        ];
    }
}
