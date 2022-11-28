<?php

namespace SkylarkSoft\GoRMG\TimeAndAction\Services;

use Throwable;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;
use SkylarkSoft\GoRMG\TimeAndAction\Models\TNAReports;
use SkylarkSoft\GoRMG\TimeAndAction\Notifications\TNATaskNotification;

class TNANotificationService
{
    public static function notifyUser($task, $order, $noticeBefore): void
    {
        $user = User::query()->find($task->user_id);
        if ($user) {
            $user->notify(new TNATaskNotification([
                    'notice_before' => $noticeBefore,
                    'order' => $order,
                    'task_id' => $task->id,
                    'task_name' => $task->task_name,
                ])
            );
        }
    }

    public static function notification($tnaReportData, $order, $noticeBefore = null): void
    {
        $task = $tnaReportData->task;
        $data = Notification::query()
            ->where('notifiable_id', $task->user_id)
            ->whereJsonContains('data', [
                'task_id' => $task->id,
                'job_no' => $order['job_no']
            ])->first();

        if (!$data) {
            static::notifyUser($task, $order, $noticeBefore);
            if ($noticeBefore) {
                $tnaReportData->update(['notice_before_notified' => true]);
            }
        } else if ($tnaReportData->notice_before_notified === 0) {
            static::notifyUser($task, $order, $noticeBefore);
            $tnaReportData->update(['notice_before_notified' => true]);
        }

    }

    /**
     * @throws Throwable
     */
    public static function noticeBeforeNotification()
    {
        $orderData = TNAReports::query()->with(['task', 'order'])
            ->whereDate('notice_before_date', date('Y-m-d'))
            ->where('notice_before_notified', 0)
            ->whereNotNull('order_id')
            ->whereNull('po_id')
            ->get();

        $poData = TNAReports::query()->with(['task', 'purchaseOrder'])
            ->whereDate('notice_before_date', date('Y-m-d'))
            ->where('notice_before_notified', 0)
            ->whereNotNull('po_id')
            ->get();


        try {
            DB::beginTransaction();

            /*Order wise */
            foreach ($orderData as $key => $value) {
                self::notification($value, [
                    'job_no' => $value->order->job_no,
                    'buyer_id' => $value->buyer_id,
                    'factory_id' => $value->factory_id
                ], $value->notice_before);
            }

            /*PO wise */
            foreach ($poData as $key => $value) {
                self::notification($value, [
                    'job_no' => $value->purchaseOrder->order->job_no,
                    'buyer_id' => $value->buyer_id,
                    'factory_id' => $value->factory_id
                ], $value->notice_before);
            }
            DB::commit();
        } catch (Throwable $e) {
            return $e->getMessage();
        }

    }
}
