<?php

namespace SkylarkSoft\GoRMG\Subcontract\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubGoodsDeliveryNotification extends Notification
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
        return (new MailMessage())
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
        $url = '/subcontract/sub-dyeing-goods-delivery/bill-view/' . $this->data['id'];

        if ($this->data['type'] == 'created') {
            $title = "A new sub goods delivery( <span class='text-primary'>" . $this->data['goods_delivery_uid'] . " </span>) has been created.";
        } elseif ($this->data['type'] == 'updated') {
            $title = "Sub goods delivery( <span class='text-primary'>" . $this->data['goods_delivery_uid'] . " </span>)
                      " . $this->data['entry_basis_value'] . " no( <span class='text-primary'>" . $this->data['batch_order_no'] . " </span>)
                      has been updated. Changes: <span class='text-primary'>" . $this->data['changes'] . "</span>";
        } else {
            $title = "Sub goods delivery( <span class='text-primary'>" . $this->data['goods_delivery_uid'] . " </span>) has been deleted.";
            $url = '/subcontract/sub-dyeing-goods-delivery';
        }

        return [
            'url' => $url,
            'title' => $title,
        ];
    }
}
