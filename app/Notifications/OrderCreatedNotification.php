<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Services\WhatsAppService;

class OrderCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected Order $order)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your order has been received - ' . $this->order->order_number)
            ->greeting('Hi ' . $this->order->customer_name . ',')
            ->line('Thank you for your order!')
            ->line('Order number: ' . $this->order->order_number)
            ->line('Order total: ' . $this->order->formatted_total)
            ->action('View your order', route('customer.orders.show', $this->order))
            ->line('We will notify you when your order status is updated.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'order_created',
            'title' => 'New Order Received',
            'message' => "New order #{$this->order->order_number} from {$this->order->customer_name}",
            'amount' => $this->order->total_amount,
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'icon' => 'solar:bag-heart-bold-duotone',
            'icon_color' => 'success',
            'url' => route('admin.orders.show', $this->order),
        ];
    }
}
