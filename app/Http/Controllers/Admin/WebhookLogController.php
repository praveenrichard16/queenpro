<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebhookLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WebhookLogController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $status = $request->string('status')->toString();
        $eventType = $request->string('event_type')->toString();
        
        $query = WebhookLog::with('endpoint')->latest();
        
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('event_type', 'like', "%{$search}%")
                    ->orWhere('source', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }
        
        if ($status !== '' && $status !== 'all') {
            $query->where('status', $status);
        }
        
        if ($eventType !== '' && $eventType !== 'all') {
            $query->where('event_type', $eventType);
        }
        
        $logs = $query->paginate(20)->withQueryString();
        
        // Statistics
        $totalLogs = WebhookLog::count();
        $successful = WebhookLog::successful()->count();
        $failed = WebhookLog::failed()->count();
        $pending = WebhookLog::pending()->count();
        
        return view('admin.api.webhook-logs.index', [
            'logs' => $logs,
            'search' => $search,
            'status' => $status,
            'eventType' => $eventType,
            'totalLogs' => $totalLogs,
            'successful' => $successful,
            'failed' => $failed,
            'pending' => $pending,
        ]);
    }
}

