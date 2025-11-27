<?php

use App\Models\Setting;

try {
    $supportEmailEnabled = (bool) Setting::getValue('support_email_enabled', true);
    $supportNotifyAddresses = Setting::getValue('support_notify_addresses', null);
    $supportCustomerUpdates = (bool) Setting::getValue('support_email_customer_updates', true);
    $supportDefaultSlaId = Setting::getValue('support_default_sla_id', null);
} catch (\Throwable) {
    $supportEmailEnabled = true;
    $supportNotifyAddresses = null;
    $supportCustomerUpdates = true;
    $supportDefaultSlaId = null;
}

return [
    'email' => [
        'enabled' => $supportEmailEnabled,
        'notify_addresses' => $supportNotifyAddresses,
        'send_customer_updates' => $supportCustomerUpdates,
    ],
    'default_sla_id' => $supportDefaultSlaId,
];

