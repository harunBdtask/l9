<?php

namespace SkylarkSoft\GoRMG\TimeAndAction\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TNATaskNotification extends Notification
{
    use Queueable;

    private $data;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable): array
    {
        $data = $this->data;
        $jobNo = $data['order']['job_no'];
        $url = '/order-entry-report?' . http_build_query([
                'factory_id' => $data['order']['factory_id'],
                'buyer_id' => $data['order']['buyer_id'],
                'job_no' => $jobNo, 'type' => 'po_details'
            ]);

        if ($data['notice_before']) {
            $dayOrDays = $data['notice_before'] == 1 ? 'day' : 'days';
            $title = 'Reminder : You have only ' . $data['notice_before'] . ' ' . $dayOrDays . ' to complete <span class="text-primary"> ' . $data['task_name'] . ' </span> in order (<span class="text-primary"> ' . $jobNo . ' </span>)';
        } else {
            $title = 'You are assigned for <span class="text-primary"> ' . $data['task_name'] . ' </span> in order (<span class="text-primary"> ' . $jobNo . ' </span>)';
        }

        return [
            'url' => $url,
            'title' => $title,
        ];
    }
}
