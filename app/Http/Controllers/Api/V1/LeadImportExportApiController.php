<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\ApiBaseController;
use App\Services\LeadImportExportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LeadImportExportApiController extends ApiBaseController
{
    public function export(Request $request, LeadImportExportService $service): StreamedResponse|JsonResponse
    {
        if (!$this->hasPermission('read')) {
            return $this->forbiddenResponse('You do not have permission to export leads');
        }

        $filters = $request->only(['lead_stage_id', 'lead_source_id', 'assigned_to', 'search']);
        $format = $request->get('format', 'csv');

        return $service->export($filters, $format);
    }

    public function import(Request $request, LeadImportExportService $service): JsonResponse
    {
        if (!$this->hasPermission('write')) {
            return $this->forbiddenResponse('You do not have permission to import leads');
        }

        $data = $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:10240'],
            'mode' => ['nullable', 'in:create,update,replace'],
        ]);

        $summary = $service->import($data['file'], $data['mode'] ?? 'create');

        return $this->successResponse($summary, 'Leads imported successfully');
    }
}

