<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\ApiBaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class CommandApiController extends ApiBaseController
{
    /**
     * Execute a system command (admin only)
     */
    public function execute(Request $request): JsonResponse
    {
        if (!$this->hasPermission('admin')) {
            return $this->forbiddenResponse('You do not have permission to execute commands');
        }

        $validated = $request->validate([
            'command' => 'required|string',
            'parameters' => 'nullable|array',
        ]);

        $command = $validated['command'];
        $parameters = $validated['parameters'] ?? [];

        // List of allowed safe commands
        $allowedCommands = [
            'cache:clear',
            'config:clear',
            'route:clear',
            'view:clear',
            'optimize:clear',
            'queue:work',
            'queue:restart',
        ];

        if (!in_array($command, $allowedCommands)) {
            return $this->errorResponse('Command not allowed', 403);
        }

        try {
            $exitCode = Artisan::call($command, $parameters);
            $output = Artisan::output();

            // Log the command execution
            Log::info('API Command Executed', [
                'command' => $command,
                'parameters' => $parameters,
                'user_id' => auth()->id(),
                'exit_code' => $exitCode,
            ]);

            return $this->successResponse([
                'command' => $command,
                'exit_code' => $exitCode,
                'output' => $output,
            ], 'Command executed successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Command execution failed: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get list of available commands
     */
    public function list(): JsonResponse
    {
        if (!$this->hasPermission('read')) {
            return $this->forbiddenResponse();
        }

        $commands = [
            [
                'command' => 'cache:clear',
                'description' => 'Clear application cache',
            ],
            [
                'command' => 'config:clear',
                'description' => 'Clear configuration cache',
            ],
            [
                'command' => 'route:clear',
                'description' => 'Clear route cache',
            ],
            [
                'command' => 'view:clear',
                'description' => 'Clear compiled view files',
            ],
            [
                'command' => 'optimize:clear',
                'description' => 'Clear all cached files',
            ],
        ];

        return $this->successResponse($commands, 'Available commands retrieved successfully');
    }
}

