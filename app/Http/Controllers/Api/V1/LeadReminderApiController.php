<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\ApiBaseController;
use App\Services\LeadReminderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeadReminderApiController extends ApiBaseController
{
    public function run(Request $request, LeadReminderService $service): JsonResponse
    {
        if (!$this->hasPermission('write')) {
            return $this->forbiddenResponse('You do not have permission to trigger reminders');
        }

        $window = (int) $request->get('window', config('lead_reminders.window_minutes', 60));

        $result = $service->sendDueReminders($window);

        return $this->successResponse($result, 'Reminders processed successfully');
    }
}

