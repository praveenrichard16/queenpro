<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Services\WhatsAppService;

class OrderStatusChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected Order $order,
        protected string $oldStatus,
        protected string $newStatus
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $statusLabels = [
            'pending' => 'Pending',
            'processing' => 'Processing',
            'in_transit' => 'In Transit',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
            'rejected' => 'Rejected',
        ];

        $oldLabel = $statusLabels[$this->oldStatus] ?? $this->oldStatus;
        $newLabel = $statusLabels[$this->newStatus] ?? $this->newStatus;

        return (new MailMessage)
            ->subject('Your order status has been updated - ' . $this->order->order_number)
            ->greeting('Hi ' . $this->order->customer_name . ',')
            ->line("The status of your order {$this->order->order_number} has changed.")
            ->line("Previous status: {$oldLabel}")
            ->line("New status: {$newLabel}")
            ->action('View your order', route('customer.orders.show', $this->order))
            ->line('Thank you for shopping with us!');
    }

    public function toArray(object $notifiable): array
    {
        $statusLabels = [
            'pending' => 'Pending',
            'processing' => 'Processing',
            'in_transit' => 'In Transit',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
            'rejected' => 'Rejected',
        ];

        $oldLabel = $statusLabels[$this->oldStatus] ?? $this->oldStatus;
        $newLabel = $statusLabels[$this->newStatus] ?? $this->newStatus;

        $icon = match($this->newStatus) {
            'delivered' => 'solar:check-circle-bold-duotone',
            'cancelled', 'rejected' => 'solar:close-circle-bold-duotone',
            'in_transit' => 'solar:delivery-bold-duotone',
            default => 'solar:bag-check-bold-duotone',
        };

        $iconColor = match($this->newStatus) {
            'delivered' => 'success',
            'cancelled', 'rejected' => 'danger',
            'in_transit' => 'info',
            default => 'warning',
        };

        return [
            'type' => 'order_status_changed',
            'title' => 'Order Status Updated',
            'message' => "Order #{$this->order->order_number} status changed from {$oldLabel} to {$newLabel}",
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'icon' => $icon,
            'icon_color' => $iconColor,
            'url' => route('admin.orders.show', $this->order),
        ];
    }
}
