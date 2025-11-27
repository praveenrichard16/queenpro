<?php

namespace App\Notifications;

use App\Models\AffiliateCommission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AffiliateCommissionApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected AffiliateCommission $commission)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $this->commission->load('order');
        $orderNumber = $this->commission->order->order_number ?? 'N/A';
        
        return [
            'type' => 'affiliate_commission_approved',
            'title' => 'Commission Approved',
            'message' => "Your commission of " . \App\Services\CurrencyService::format($this->commission->amount) . " for order #{$orderNumber} has been approved",
            'amount' => $this->commission->amount,
            'commission_id' => $this->commission->id,
            'order_id' => $this->commission->order_id,
            'icon' => 'solar:dollar-minimalistic-bold-duotone',
            'icon_color' => 'success',
            'url' => route('customer.affiliates.index'),
        ];
    }
}
