<?php

namespace App\Notifications;

use App\Models\AffiliatePayout;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AffiliatePayoutProcessedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected AffiliatePayout $payout)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $statusMessage = match($this->payout->status) {
            'paid' => 'has been processed',
            'processing' => 'is being processed',
            'failed' => 'failed to process',
            default => 'is pending',
        };

        return [
            'type' => 'affiliate_payout_processed',
            'title' => 'Payout ' . ucfirst($this->payout->status),
            'message' => "Your payout of " . \App\Services\CurrencyService::format($this->payout->total_amount) . " {$statusMessage}",
            'amount' => $this->payout->total_amount,
            'payout_id' => $this->payout->id,
            'status' => $this->payout->status,
            'icon' => match($this->payout->status) {
                'paid' => 'solar:wallet-money-bold-duotone',
                'failed' => 'solar:close-circle-bold-duotone',
                default => 'solar:clock-circle-bold-duotone',
            },
            'icon_color' => match($this->payout->status) {
                'paid' => 'success',
                'failed' => 'danger',
                default => 'warning',
            },
            'url' => route('customer.affiliates.index'),
        ];
    }
}
