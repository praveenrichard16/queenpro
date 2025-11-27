<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarketingTemplate;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class MarketingTemplateController extends Controller
{
    public function index(Request $request): View
    {
        if (!Schema::hasTable('marketing_templates')) {
            $templates = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20, 1);
            return view('admin.marketing.templates.index', compact('templates'));
        }

        $query = MarketingTemplate::query();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $templates = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.marketing.templates.index', compact('templates'));
    }

    public function create(): View
    {
        return view('admin.marketing.templates.form', [
            'template' => new MarketingTemplate(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:email,whatsapp,push_notification'],
            'subject' => ['nullable', 'string', 'max:255'],
            'content' => ['required_unless:type,whatsapp', 'string'],
            'language' => ['nullable', 'string', 'max:50'],
            'category' => ['nullable', 'string', 'max:50'],
            'variable_tokens' => ['nullable', 'string'],
            'whatsapp_body' => ['required_if:type,whatsapp', 'string'],
            'whatsapp_header' => ['nullable', 'string'],
            'whatsapp_footer' => ['nullable', 'string'],
            'cta_button_text' => ['nullable', 'string', 'max:60'],
            'cta_button_url' => ['nullable', 'url'],
            'email_layout' => ['nullable', 'string', 'max:50'],
            'whatsapp_template_id' => ['nullable', 'string', 'max:255'],
            'whatsapp_template_status' => ['nullable', 'in:pending,approved,rejected'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $data = $this->prepareTemplateData($request, $data);

        MarketingTemplate::create($data);

        return redirect()->route('admin.marketing.templates.index')
            ->with('success', 'Template created successfully.');
    }

    public function edit(MarketingTemplate $template): View
    {
        return view('admin.marketing.templates.form', compact('template'));
    }

    public function update(Request $request, MarketingTemplate $template): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:email,whatsapp,push_notification'],
            'subject' => ['nullable', 'string', 'max:255'],
            'content' => ['required_unless:type,whatsapp', 'string'],
            'language' => ['nullable', 'string', 'max:50'],
            'category' => ['nullable', 'string', 'max:50'],
            'variable_tokens' => ['nullable', 'string'],
            'whatsapp_body' => ['required_if:type,whatsapp', 'string'],
            'whatsapp_header' => ['nullable', 'string'],
            'whatsapp_footer' => ['nullable', 'string'],
            'cta_button_text' => ['nullable', 'string', 'max:60'],
            'cta_button_url' => ['nullable', 'url'],
            'email_layout' => ['nullable', 'string', 'max:50'],
            'whatsapp_template_id' => ['nullable', 'string', 'max:255'],
            'whatsapp_template_status' => ['nullable', 'in:pending,approved,rejected'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $data = $this->prepareTemplateData($request, $data, $template);

        $template->update($data);

        return redirect()->route('admin.marketing.templates.index')
            ->with('success', 'Template updated successfully.');
    }

    public function destroy(MarketingTemplate $template): RedirectResponse
    {
        $template->delete();

        return redirect()->route('admin.marketing.templates.index')
            ->with('success', 'Template deleted successfully.');
    }

    /**
     * Get fresh WhatsApp config from database settings
     */
    protected function getWhatsAppConfig(): array
    {
        // Clear cache to ensure fresh data
        \Illuminate\Support\Facades\Cache::forget('setting_integration_whatsapp_meta');
        
        $whatsappMeta = Setting::getValue('integration_whatsapp_meta', []);
        
        if (!is_array($whatsappMeta)) {
            $whatsappMeta = [];
        }

        return [
            'enabled' => $whatsappMeta['enabled'] ?? false,
            'api_token' => $whatsappMeta['access_token'] ?? null,
            'phone_number_id' => $whatsappMeta['phone_number_id'] ?? null,
            'whatsapp_business_account_id' => $whatsappMeta['whatsapp_business_account_id'] ?? $whatsappMeta['business_account_id'] ?? null,
            'version' => $whatsappMeta['api_version'] ?? 'v19.0',
            'language' => $whatsappMeta['language'] ?? 'en',
        ];
    }

    public function syncWhatsAppTemplates(): RedirectResponse
    {
        // Get fresh config from database
        $config = $this->getWhatsAppConfig();
        
        if (empty($config['enabled']) || empty($config['api_token'])) {
            return back()->with('error', 'WhatsApp is not configured. Please configure it in Integration Settings first.');
        }

        try {
            // Get WhatsApp Business Account ID
            $businessAccountId = $config['whatsapp_business_account_id'] ?? null;
            
            if (!$businessAccountId) {
                // Try to get from phone number if available
                if (!empty($config['phone_number_id'])) {
                    Log::info('Business Account ID not in config, fetching from phone number metadata');
                    
                    $maxRetries = 3;
                    $retryDelay = 2;
                    $response = null;
                    
                    for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
                        try {
                            $response = \Illuminate\Support\Facades\Http::withToken($config['api_token'])
                                ->timeout(30)
                                ->get("https://graph.facebook.com/{$config['version']}/{$config['phone_number_id']}", [
                                    'fields' => 'whatsapp_business_account_id',
                                ]);

                            if ($response && $response->successful()) {
                                $data = $response->json();
                                $businessAccountId = $data['whatsapp_business_account_id'] ?? null;
                                if ($businessAccountId) {
                                    Log::info('Retrieved Business Account ID from phone number', [
                                        'business_account_id' => $businessAccountId,
                                    ]);
                                    break;
                                }
                            }
                        } catch (\Illuminate\Http\Client\ConnectionException $e) {
                            if ($attempt < $maxRetries) {
                                Log::warning("Phone number metadata fetch attempt {$attempt} failed, retrying...", [
                                    'error' => $e->getMessage(),
                                ]);
                                sleep($retryDelay);
                                continue;
                            } else {
                                Log::error('Failed to fetch phone number metadata after retries', [
                                    'error' => $e->getMessage(),
                                ]);
                            }
                        }
                    }
                }
            }

            if (!$businessAccountId) {
                return back()->with('error', 'WhatsApp Business Account ID not found. Please configure it in Integration Settings.');
            }

            // Fetch templates from WhatsApp Business Account
            $allTemplates = [];
            $nextUrl = "https://graph.facebook.com/{$config['version']}/{$businessAccountId}/message_templates?limit=100";

            // Handle pagination with retry logic
            do {
                $maxRetries = 3;
                $retryDelay = 2;
                $response = null;
                
                for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
                    try {
                        $response = \Illuminate\Support\Facades\Http::withToken($config['api_token'])
                            ->timeout(30)
                            ->get($nextUrl);

                        if ($response && $response->successful()) {
                            break;
                        }
                        
                        // If not retryable error, break
                        if ($response && !$this->isRetryableError($response)) {
                            break;
                        }
                        
                        if ($attempt < $maxRetries) {
                            Log::warning("Template fetch attempt {$attempt} failed, retrying...", [
                                'url' => $nextUrl,
                            ]);
                            sleep($retryDelay);
                        }
                    } catch (\Illuminate\Http\Client\ConnectionException $e) {
                        if ($attempt < $maxRetries) {
                            Log::warning("Template fetch attempt {$attempt} failed, retrying...", [
                                'error' => $e->getMessage(),
                                'url' => $nextUrl,
                            ]);
                            sleep($retryDelay);
                            continue;
                        } else {
                            throw new \Exception('Connection timeout after ' . $maxRetries . ' attempts while fetching templates: ' . $e->getMessage());
                        }
                    }
                }

                if (!$response) {
                    throw new \Exception('Failed to get response from Meta API after ' . $maxRetries . ' attempts.');
                }

                if ($response->failed()) {
                    $errorBody = $response->json();
                    $errorMessage = $errorBody['error']['message'] ?? $response->body();
                    $errorCode = $errorBody['error']['code'] ?? null;
                    
                    // Provide more specific error messages
                    if ($errorCode == 200 || $errorCode == 190) {
                        throw new \Exception('Authentication failed. Please check your access token is valid and has not expired.');
                    } elseif ($errorCode == 10) {
                        throw new \Exception('Permission denied. Your access token does not have the required permissions. Please check your token permissions in Meta Business Manager.');
                    } elseif ($errorCode == 100) {
                        throw new \Exception('Invalid parameter. The Business Account ID may be incorrect. Please verify it in Meta Business Manager.');
                    }
                    
                    throw new \Exception('Failed to fetch templates: ' . $errorMessage);
                }

                $data = $response->json();
                $templates = $data['data'] ?? [];
                $allTemplates = array_merge($allTemplates, $templates);

                // Check for next page
                $paging = $data['paging'] ?? [];
                $nextUrl = $paging['next'] ?? null;

            } while ($nextUrl);

            if (empty($allTemplates)) {
                return back()->with('warning', 'No templates found in your WhatsApp Business Account. Create templates in Meta Business Manager first.');
            }

            $synced = 0;
            $updated = 0;
            $created = 0;

            foreach ($allTemplates as $templateData) {
                $templateId = $templateData['id'] ?? null;
                $templateName = $templateData['name'] ?? 'Untitled Template';
                $templateStatus = $templateData['status'] ?? 'pending';
                $templateLanguage = $templateData['language'] ?? 'en';
                $templateCategory = strtoupper($templateData['category'] ?? 'MARKETING');
                
                // Extract template content from components
                $components = $templateData['components'] ?? [];
                $bodyText = '';
                $headerText = '';
                $footerText = '';
                $variables = [];

                foreach ($components as $component) {
                    $componentType = $component['type'] ?? '';
                    
                    if ($componentType === 'BODY') {
                        $bodyText = $component['text'] ?? '';
                        // Extract variables from body
                        if (isset($component['example'])) {
                            $variables['body'] = $component['example']['body_text'] ?? [];
                        }
                    } elseif ($componentType === 'HEADER') {
                        if (isset($component['text'])) {
                            $headerText = $component['text'];
                        } elseif (isset($component['format']) && $component['format'] === 'IMAGE') {
                            $headerText = '[IMAGE]';
                        } elseif (isset($component['format']) && $component['format'] === 'VIDEO') {
                            $headerText = '[VIDEO]';
                        } elseif (isset($component['format']) && $component['format'] === 'DOCUMENT') {
                            $headerText = '[DOCUMENT]';
                        }
                    } elseif ($componentType === 'FOOTER') {
                        $footerText = $component['text'] ?? '';
                    }
                }

                // Combine header, body, and footer
                $fullContent = trim(implode("\n\n", array_filter([$headerText, $bodyText, $footerText])));
                if (empty($fullContent)) {
                    $fullContent = $templateName; // Fallback to template name
                }

                $existing = MarketingTemplate::where('whatsapp_template_id', $templateId)
                    ->where('type', 'whatsapp')
                    ->first();

                // Normalize status to lowercase for comparison
                $templateStatusLower = strtolower($templateStatus);
                $isApproved = in_array($templateStatusLower, ['approved', 'active']);
                
                $metaPayload = [
                    'header' => $headerText,
                    'body' => $bodyText,
                    'footer' => $footerText,
                    'language' => $templateLanguage,
                    'category' => $templateCategory,
                ];

                if ($existing) {
                    $existing->update([
                        'name' => $templateName,
                        'content' => $fullContent,
                        'whatsapp_template_status' => $templateStatusLower,
                        'variables' => $variables,
                        'is_active' => $isApproved,
                        'language' => $templateLanguage,
                        'category' => $templateCategory,
                        'meta' => $metaPayload,
                    ]);
                    $updated++;
                } else {
                    MarketingTemplate::create([
                        'name' => $templateName,
                        'type' => 'whatsapp',
                        'subject' => null,
                        'content' => $fullContent,
                        'variables' => $variables,
                        'whatsapp_template_id' => $templateId,
                        'whatsapp_template_status' => $templateStatusLower,
                        'is_active' => $isApproved,
                        'language' => $templateLanguage,
                        'category' => $templateCategory,
                        'meta' => $metaPayload,
                    ]);
                    $created++;
                }
                $synced++;
            }

            $message = "Successfully synced {$synced} WhatsApp template(s). ";
            if ($created > 0) {
                $message .= "Created: {$created}. ";
            }
            if ($updated > 0) {
                $message .= "Updated: {$updated}.";
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            Log::error('WhatsApp template sync failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->with('error', 'Failed to sync templates: ' . $e->getMessage());
        }
    }

    /**
     * Check if an error is retryable (network/timeout errors)
     */
    protected function isRetryableError($response): bool
    {
        if ($response === null) {
            return true;
        }
        
        // Check for connection exceptions
        if (method_exists($response, 'toException')) {
            $exception = $response->toException();
            if ($exception instanceof \Illuminate\Http\Client\ConnectionException) {
                return true;
            }
        }
        
        // Check for timeout errors in response
        $body = $response->body();
        if (stripos($body, 'timeout') !== false || 
            stripos($body, 'connection') !== false ||
            stripos($body, 'curl error 28') !== false) {
            return true;
        }
        
        return false;
    }

    protected function prepareTemplateData(Request $request, array $data, ?MarketingTemplate $template = null): array
    {
        $existingVariables = $template?->variables ?? [];
        $data['variables'] = $this->parseVariableTokens($request->input('variable_tokens'), $existingVariables);

        if ($data['type'] === 'whatsapp') {
            $header = $request->input('whatsapp_header');
            $body = $request->input('whatsapp_body');
            $footer = $request->input('whatsapp_footer');
            $ctaText = $request->input('cta_button_text');
            $ctaUrl = $request->input('cta_button_url');

            $data['subject'] = null;
            $data['language'] = $request->input('language', $template?->language ?? 'en');
            $data['category'] = $request->input('category', $template?->category ?? 'MARKETING');
            $data['content'] = $this->buildWhatsappContent($header, $body, $footer, $ctaText, $ctaUrl);
            $data['meta'] = [
                'header' => $header,
                'body' => $body,
                'footer' => $footer,
                'cta' => [
                    'text' => $ctaText,
                    'url' => $ctaUrl,
                ],
            ];
        } else {
            $data['language'] = $request->input('language', $template?->language);
            $data['category'] = $request->input('category', $template?->category);
            $data['meta'] = [
                'layout' => $request->input('email_layout', data_get($template?->meta, 'layout', 'basic')),
            ];
            $data['content'] = $request->input('content', $template?->content);
        }

        unset(
            $data['variable_tokens'],
            $data['whatsapp_header'],
            $data['whatsapp_body'],
            $data['whatsapp_footer'],
            $data['cta_button_text'],
            $data['cta_button_url'],
            $data['email_layout']
        );

        return $data;
    }

    protected function parseVariableTokens(?string $tokens, array $fallback = []): array
    {
        if ($tokens === null) {
            return $fallback ?? [];
        }

        return collect(explode(',', $tokens))
            ->map(fn ($token) => trim($token))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    protected function buildWhatsappContent(?string $header, ?string $body, ?string $footer, ?string $ctaText, ?string $ctaUrl): string
    {
        $parts = array_filter([$header, $body, $footer]);

        if ($ctaText && $ctaUrl) {
            $parts[] = "CTA: {$ctaText} - {$ctaUrl}";
        }

        return trim(implode("\n\n", $parts));
    }
}

