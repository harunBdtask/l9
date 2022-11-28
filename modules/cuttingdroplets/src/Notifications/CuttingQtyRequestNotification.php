<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CuttingQtyRequestNotification extends Notification
{
    use Queueable;

    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    private function generateUrl(): string
    {
        return route('cutting-qty-approval', [
            'buyer_id' => $this->data['buyer_id'],
            'order_id' => $this->data['order_id'],
            'item_id' => $this->data['item_id'],
            'po_id' => $this->data['po_id'],
            'color_id' => $this->data['color_id'],
        ]);
    }

    private function generateTitle(): string
    {
        return "Cutting Qty Request for"
            . " Buyer ({$this->data['buyer']->name}),"
            . " Style ({$this->data['order']->style_name}),"
            . " PO NO ({$this->data['purchaseOrder']->po_no}),"
            . " Color ({$this->data['color']['name']})";
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
            ->subject('Cutting Quantity Request')
            ->view(
                'cuttingdroplets::mail-notifications.cutting-qty-request-mail',
                [
                    'remarks' => $this->data->remarks,
                    'title' => $this->generateTitle(),
                    'url' => $this->generateUrl(),
                ]
            );
    }

    public function toArray($notifiable): array
    {
        return [
            'url' => $this->generateUrl(),
            'title' => $this->generateTitle(),
        ];
    }
}
