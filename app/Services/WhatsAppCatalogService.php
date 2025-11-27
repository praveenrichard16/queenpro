<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppCatalogService
{
    /**
     * Get fresh WhatsApp config from database settings
     */
    protected function getConfig(): array
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

    /**
     * Sync a product to WhatsApp Business Catalog
     */
    public function syncProduct(Product $product): bool
    {
        $config = $this->getConfig();
        
        if (empty($config['enabled']) || empty($config['api_token']) || empty($config['phone_number_id'])) {
            $errorMsg = 'WhatsApp is not configured. Please configure WhatsApp in Integration Settings.';
            $product->update([
                'whatsapp_sync_error' => $errorMsg,
            ]);
            
            Log::warning('WhatsApp catalog sync skipped - not configured', [
                'product_id' => $product->id,
            ]);
            
            return false;
        }

        try {
            $catalogId = $this->getOrCreateCatalog($config);
            if (!$catalogId) {
                // Get the last error from logs or provide a helpful message
                $errorMsg = 'Failed to get or create catalog. ';
                
                // Check if business account ID is missing
                if (empty($config['whatsapp_business_account_id'])) {
                    $errorMsg .= 'WhatsApp Business Account ID is not configured. ';
                    $errorMsg .= 'Please add it in Integration Settings → WhatsApp Meta → WhatsApp Business Account ID field. ';
                    $errorMsg .= 'You can find this ID in your Meta Business Manager or it can be retrieved from your phone number.';
                } else {
                    $errorMsg .= 'Please check your WhatsApp Business Account ID and ensure your access token has the required permissions (catalog_management).';
                }
                
                throw new \Exception($errorMsg);
            }

            Log::info('WhatsApp catalog sync started', [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'catalog_id' => $catalogId,
                'has_existing_id' => !empty($product->whatsapp_product_id),
            ]);

            // Check if product already exists in catalog
            if ($product->whatsapp_product_id) {
                return $this->updateProduct($product, $catalogId, $config);
            }

            return $this->createProduct($product, $catalogId, $config);
        } catch (\Exception $e) {
            $errorMessage = $this->extractErrorMessage($e);
            
            Log::error('WhatsApp catalog sync failed', [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'error' => $e->getMessage(),
                'config' => [
                    'has_business_account_id' => !empty($config['whatsapp_business_account_id']),
                    'has_api_token' => !empty($config['api_token']),
                    'has_phone_number_id' => !empty($config['phone_number_id']),
                ],
                'trace' => $e->getTraceAsString(),
            ]);

            $product->update([
                'whatsapp_sync_error' => $errorMessage,
            ]);

            return false;
        }
    }

    /**
     * Get or create WhatsApp Business Catalog
     */
    protected function getOrCreateCatalog(array $config): ?string
    {
        $lastError = null;
        
        try {
            // Get business account ID from config or phone number
            $businessAccountId = $config['whatsapp_business_account_id'] ?? null;
            
            // Validate Business Account ID format if provided
            if ($businessAccountId && !preg_match('/^\d+$/', $businessAccountId)) {
                Log::warning('Invalid Business Account ID format', [
                    'business_account_id' => $businessAccountId,
                ]);
                throw new \Exception('Invalid Business Account ID format. It should be a numeric ID. Please check your Integration Settings.');
            }
            
            if (!$businessAccountId) {
                Log::info('Business Account ID not in config, fetching from phone number metadata', [
                    'phone_number_id' => $config['phone_number_id'],
                ]);
                
                // Try to get from phone number metadata
                $response = Http::withToken($config['api_token'])
                    ->timeout(30) // Increased timeout
                    ->get("https://graph.facebook.com/{$config['version']}/{$config['phone_number_id']}", [
                        'fields' => 'id,display_phone_number,whatsapp_business_account_id',
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $businessAccountId = $data['whatsapp_business_account_id'] ?? null;
                    
                    if ($businessAccountId) {
                        Log::info('Retrieved Business Account ID from phone number', [
                            'business_account_id' => $businessAccountId,
                        ]);
                    } else {
                        $lastError = 'Business Account ID not found in phone number metadata. The phone number may not be properly linked to a WhatsApp Business Account.';
                        Log::warning('Business Account ID not in phone number metadata', [
                            'phone_number_id' => $config['phone_number_id'],
                            'response_data' => $data,
                        ]);
                    }
                } else {
                    $error = $this->extractErrorMessageFromResponse($response);
                    $lastError = 'Failed to fetch phone number metadata: ' . $error;
                    Log::warning('Failed to fetch phone number metadata', [
                        'error' => $error,
                        'response' => $response->body(),
                    ]);
                }
            }

            if (!$businessAccountId) {
                $errorMsg = 'WhatsApp Business Account ID not found. ';
                if ($lastError) {
                    $errorMsg .= $lastError . ' ';
                }
                $errorMsg .= 'Please configure it manually in Integration Settings → WhatsApp Meta → WhatsApp Business Account ID field.';
                throw new \Exception($errorMsg);
            }

            // Verify Business Account ID is accessible with current access token
            try {
                Log::info('Verifying Business Account ID access', [
                    'business_account_id' => $businessAccountId,
                ]);
                
                $verifyResponse = Http::withToken($config['api_token'])
                    ->timeout(30)
                    ->get("https://graph.facebook.com/{$config['version']}/{$businessAccountId}", [
                        'fields' => 'id,name',
                    ]);

                if ($verifyResponse->failed()) {
                    $errorBody = $verifyResponse->json();
                    $errorCode = $errorBody['error']['code'] ?? null;
                    $errorMessage = $this->extractErrorMessageFromResponse($verifyResponse);
                    
                    if ($errorCode == 100) {
                        throw new \Exception('The Business Account ID "' . $businessAccountId . '" is invalid or not accessible with your current access token. Please verify: 1) The Business Account ID is correct, 2) Your access token has permission to access this Business Account, 3) The Business Account is linked to your WhatsApp Business App.');
                    } elseif ($errorCode == 200 || $errorCode == 190) {
                        throw new \Exception('Authentication failed. Your access token may be invalid or expired. Please check your access token in Integration Settings.');
                    } elseif ($errorCode == 10) {
                        throw new \Exception('Permission denied. Your access token does not have permission to access this Business Account. Please check your token permissions in Meta Business Manager.');
                    }
                    
                    throw new \Exception('Failed to verify Business Account ID: ' . $errorMessage);
                }
                
                $accountData = $verifyResponse->json();
                Log::info('Business Account ID verified successfully', [
                    'business_account_id' => $businessAccountId,
                    'account_name' => $accountData['name'] ?? 'N/A',
                ]);
            } catch (\Exception $e) {
                // If it's already our custom exception, re-throw it
                if (strpos($e->getMessage(), 'Business Account ID') !== false || 
                    strpos($e->getMessage(), 'Authentication failed') !== false ||
                    strpos($e->getMessage(), 'Permission denied') !== false) {
                    throw $e;
                }
                
                // For connection errors, log but continue (might be temporary)
                if ($this->isRetryableException($e)) {
                    Log::warning('Could not verify Business Account ID due to connection error, proceeding anyway', [
                        'error' => $e->getMessage(),
                    ]);
                } else {
                    throw $e;
                }
            }

            // Get existing catalogs
            Log::info('Fetching existing catalogs', [
                'business_account_id' => $businessAccountId,
            ]);
            
            // Retry logic for network timeouts
            $maxRetries = 3;
            $retryDelay = 2; // seconds
            $response = null;
            $lastException = null;
            
            for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
                try {
                    $response = Http::withToken($config['api_token'])
                        ->timeout(30) // Increased timeout to 30 seconds
                        ->get("https://graph.facebook.com/{$config['version']}/{$businessAccountId}/owned_product_catalogs");
                    
                    // If successful, break out of retry loop
                    if ($response && $response->successful()) {
                        break;
                    }
                    
                    // If not retryable error, break
                    if ($response && !$this->isRetryableError($response)) {
                        break;
                    }
                    
                    // If last attempt and still retryable, throw
                    if ($attempt === $maxRetries) {
                        $error = $this->extractErrorMessageFromResponse($response);
                        throw new \Exception('Connection error after ' . $maxRetries . ' attempts: ' . $error);
                    }
                    
                    // Exponential backoff: wait longer on each retry
                    $waitTime = $retryDelay * $attempt;
                    Log::warning("Catalog fetch attempt {$attempt} failed, retrying in {$waitTime}s...", [
                        'attempt' => $attempt,
                        'max_retries' => $maxRetries,
                        'wait_time' => $waitTime,
                    ]);
                    sleep($waitTime);
                    
                } catch (\Illuminate\Http\Client\ConnectionException $e) {
                    $lastException = $e;
                    // Connection timeout or network error
                    if ($attempt < $maxRetries && $this->isRetryableException($e)) {
                        // Exponential backoff: wait longer on each retry
                        $waitTime = $retryDelay * $attempt;
                        Log::warning("Catalog fetch attempt {$attempt} failed, retrying in {$waitTime}s...", [
                            'error' => $e->getMessage(),
                            'attempt' => $attempt,
                            'max_retries' => $maxRetries,
                            'wait_time' => $waitTime,
                        ]);
                        sleep($waitTime);
                        continue;
                    } else {
                        throw new \Exception('Connection error after ' . $maxRetries . ' attempts. This may be due to network issues or Meta API being temporarily unavailable. Please try again later. Error: ' . $e->getMessage());
                    }
                } catch (\Exception $e) {
                    // Check if it's a retryable error
                    if ($attempt < $maxRetries && $this->isRetryableException($e)) {
                        $waitTime = $retryDelay * $attempt;
                        Log::warning("Catalog fetch attempt {$attempt} failed with retryable error, retrying in {$waitTime}s...", [
                            'error' => $e->getMessage(),
                            'attempt' => $attempt,
                            'max_retries' => $maxRetries,
                            'wait_time' => $waitTime,
                        ]);
                        sleep($waitTime);
                        continue;
                    } else {
                        // Non-retryable error or last attempt
                        throw $e;
                    }
                }
            }
            
            // Check if we have a valid response
            if (!$response) {
                throw new \Exception('Failed to get response from Meta API after ' . $maxRetries . ' attempts.');
            }

            if ($response->successful()) {
                $catalogs = $response->json('data', []);
                if (!empty($catalogs)) {
                    Log::info('Found existing catalog', [
                        'catalog_id' => $catalogs[0]['id'],
                        'catalog_name' => $catalogs[0]['name'] ?? 'N/A',
                    ]);
                    return $catalogs[0]['id'];
                }
            } else {
                $error = $this->extractErrorMessageFromResponse($response);
                $lastError = 'Failed to fetch catalogs: ' . $error;
                Log::warning('Failed to fetch catalogs, attempting to create', [
                    'error' => $error,
                    'response' => $response->body(),
                    'business_account_id' => $businessAccountId,
                ]);
                
                // Check if it's a permission error
                $errorBody = $response->json();
                if (isset($errorBody['error']['code']) && in_array($errorBody['error']['code'], [200, 190, 10])) {
                    throw new \Exception('Permission denied. Your access token may not have the required permissions (catalog_management). Please check your token permissions in Meta Business Manager.');
                }
            }

            // Create new catalog if none exists
            Log::info('Creating new catalog', [
                'business_account_id' => $businessAccountId,
                'catalog_name' => config('app.name') . ' Catalog',
            ]);
            
            // Retry logic for catalog creation
            $maxRetries = 3;
            $retryDelay = 2; // seconds
            $response = null;
            
            for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
                try {
                    $response = Http::withToken($config['api_token'])
                        ->timeout(30) // Increased timeout to 30 seconds
                        ->post("https://graph.facebook.com/{$config['version']}/{$businessAccountId}/owned_product_catalogs", [
                            'name' => config('app.name') . ' Catalog',
                        ]);
                    
                    // If successful, break out of retry loop
                    if ($response && $response->successful()) {
                        break;
                    }
                    
                    // If not retryable error, break
                    if ($response && !$this->isRetryableError($response)) {
                        break;
                    }
                    
                    // If last attempt and still retryable, throw
                    if ($attempt === $maxRetries) {
                        $error = $this->extractErrorMessageFromResponse($response);
                        throw new \Exception('Connection error after ' . $maxRetries . ' attempts while creating catalog: ' . $error);
                    }
                    
                    // Exponential backoff
                    $waitTime = $retryDelay * $attempt;
                    Log::warning("Catalog creation attempt {$attempt} failed, retrying in {$waitTime}s...", [
                        'attempt' => $attempt,
                        'max_retries' => $maxRetries,
                        'wait_time' => $waitTime,
                    ]);
                    sleep($waitTime);
                    
                } catch (\Illuminate\Http\Client\ConnectionException $e) {
                    // Connection timeout or network error
                    if ($attempt < $maxRetries && $this->isRetryableException($e)) {
                        $waitTime = $retryDelay * $attempt;
                        Log::warning("Catalog creation attempt {$attempt} failed, retrying in {$waitTime}s...", [
                            'error' => $e->getMessage(),
                            'attempt' => $attempt,
                            'max_retries' => $maxRetries,
                            'wait_time' => $waitTime,
                        ]);
                        sleep($waitTime);
                        continue;
                    } else {
                        throw new \Exception('Connection error after ' . $maxRetries . ' attempts while creating catalog. This may be due to network issues or Meta API being temporarily unavailable. Please try again later. Error: ' . $e->getMessage());
                    }
                } catch (\Exception $e) {
                    if ($attempt < $maxRetries && $this->isRetryableException($e)) {
                        $waitTime = $retryDelay * $attempt;
                        Log::warning("Catalog creation attempt {$attempt} failed with retryable error, retrying in {$waitTime}s...", [
                            'error' => $e->getMessage(),
                            'attempt' => $attempt,
                        ]);
                        sleep($waitTime);
                        continue;
                    } else {
                        throw $e;
                    }
                }
            }
            
            // Check if we have a valid response
            if (!$response) {
                throw new \Exception('Failed to get response from Meta API after ' . $maxRetries . ' attempts while creating catalog.');
            }

            if ($response->successful()) {
                $catalogId = $response->json('id');
                Log::info('Catalog created successfully', [
                    'catalog_id' => $catalogId,
                ]);
                return $catalogId;
            }

            $error = $this->extractErrorMessageFromResponse($response);
            $errorBody = $response->json();
            
            // Provide more specific error messages
            if (isset($errorBody['error']['code'])) {
                $errorCode = $errorBody['error']['code'];
                if ($errorCode == 200 || $errorCode == 190) {
                    throw new \Exception('Authentication failed. Please check your access token is valid and has not expired.');
                } elseif ($errorCode == 10) {
                    throw new \Exception('Permission denied. Your access token does not have catalog_management permission. Please add this permission in Meta Business Manager.');
                } elseif ($errorCode == 100) {
                    throw new \Exception('Invalid parameter. The Business Account ID may be incorrect. Please verify it in Meta Business Manager.');
                }
            }
            
            throw new \Exception('Failed to create catalog: ' . $error);
        } catch (\Exception $e) {
            Log::error('Failed to get or create WhatsApp catalog', [
                'error' => $e->getMessage(),
                'config' => [
                    'has_business_account_id' => !empty($config['whatsapp_business_account_id']),
                    'has_api_token' => !empty($config['api_token']),
                    'has_phone_number_id' => !empty($config['phone_number_id']),
                ],
                'trace' => $e->getTraceAsString(),
            ]);
            
            // Re-throw the exception so the error message is preserved
            throw $e;
        }
    }

    /**
     * Create a new product in WhatsApp catalog
     */
    protected function createProduct(Product $product, string $catalogId, array $config): bool
    {
        $payload = $this->buildProductPayload($product);

        Log::info('Creating product in WhatsApp catalog', [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'catalog_id' => $catalogId,
            'payload' => array_merge($payload, ['image_url' => '...']), // Don't log full image URL
        ]);

        // Retry logic for product creation
        $maxRetries = 3;
        $retryDelay = 2;
        $response = null;
        
        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                $response = Http::withToken($config['api_token'])
                    ->timeout(30)
                    ->post("https://graph.facebook.com/{$config['version']}/{$catalogId}/products", $payload);
                
                if ($response && $response->successful()) {
                    break;
                }
                
                if ($response && !$this->isRetryableError($response)) {
                    break;
                }
                
                if ($attempt === $maxRetries) {
                    $error = $this->extractErrorMessageFromResponse($response);
                    throw new \Exception('Connection error after ' . $maxRetries . ' attempts: ' . $error);
                }
                
                $waitTime = $retryDelay * $attempt;
                Log::warning("Product creation attempt {$attempt} failed, retrying in {$waitTime}s...", [
                    'product_id' => $product->id,
                    'attempt' => $attempt,
                    'wait_time' => $waitTime,
                ]);
                sleep($waitTime);
                
            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                if ($attempt < $maxRetries && $this->isRetryableException($e)) {
                    $waitTime = $retryDelay * $attempt;
                    Log::warning("Product creation attempt {$attempt} failed, retrying in {$waitTime}s...", [
                        'product_id' => $product->id,
                        'error' => $e->getMessage(),
                        'wait_time' => $waitTime,
                    ]);
                    sleep($waitTime);
                    continue;
                } else {
                    throw new \Exception('Connection error while creating product. This may be due to network issues or Meta API being temporarily unavailable. Please try again later. Error: ' . $e->getMessage());
                }
            } catch (\Exception $e) {
                if ($attempt < $maxRetries && $this->isRetryableException($e)) {
                    $waitTime = $retryDelay * $attempt;
                    Log::warning("Product creation attempt {$attempt} failed with retryable error, retrying in {$waitTime}s...", [
                        'product_id' => $product->id,
                        'error' => $e->getMessage(),
                    ]);
                    sleep($waitTime);
                    continue;
                } else {
                    throw $e;
                }
            }
        }

        if ($response->successful()) {
            $productId = $response->json('id');
            $product->update([
                'whatsapp_product_id' => $productId,
                'is_synced_to_whatsapp' => true,
                'whatsapp_synced_at' => now(),
                'whatsapp_sync_error' => null,
            ]);
            
            Log::info('Product created successfully in WhatsApp catalog', [
                'product_id' => $product->id,
                'whatsapp_product_id' => $productId,
            ]);
            
            return true;
        }

        $error = $this->extractErrorMessageFromResponse($response);
        throw new \Exception('Failed to create product: ' . $error);
    }

    /**
     * Update existing product in WhatsApp catalog
     */
    protected function updateProduct(Product $product, string $catalogId, array $config): bool
    {
        $payload = $this->buildProductPayload($product);

        Log::info('Updating product in WhatsApp catalog', [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'whatsapp_product_id' => $product->whatsapp_product_id,
            'catalog_id' => $catalogId,
            'payload' => array_merge($payload, ['image_url' => '...']), // Don't log full image URL
        ]);

        // Retry logic for product update
        $maxRetries = 3;
        $retryDelay = 2;
        $response = null;
        
        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                $response = Http::withToken($config['api_token'])
                    ->timeout(30)
                    ->patch("https://graph.facebook.com/{$config['version']}/{$product->whatsapp_product_id}", $payload);
                
                if ($response && $response->successful()) {
                    break;
                }
                
                if ($response && !$this->isRetryableError($response)) {
                    break;
                }
                
                if ($attempt === $maxRetries) {
                    $error = $this->extractErrorMessageFromResponse($response);
                    throw new \Exception('Connection error after ' . $maxRetries . ' attempts: ' . $error);
                }
                
                $waitTime = $retryDelay * $attempt;
                Log::warning("Product update attempt {$attempt} failed, retrying in {$waitTime}s...", [
                    'product_id' => $product->id,
                    'attempt' => $attempt,
                    'wait_time' => $waitTime,
                ]);
                sleep($waitTime);
                
            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                if ($attempt < $maxRetries && $this->isRetryableException($e)) {
                    $waitTime = $retryDelay * $attempt;
                    Log::warning("Product update attempt {$attempt} failed, retrying in {$waitTime}s...", [
                        'product_id' => $product->id,
                        'error' => $e->getMessage(),
                        'wait_time' => $waitTime,
                    ]);
                    sleep($waitTime);
                    continue;
                } else {
                    throw new \Exception('Connection error while updating product. This may be due to network issues or Meta API being temporarily unavailable. Please try again later. Error: ' . $e->getMessage());
                }
            } catch (\Exception $e) {
                if ($attempt < $maxRetries && $this->isRetryableException($e)) {
                    $waitTime = $retryDelay * $attempt;
                    Log::warning("Product update attempt {$attempt} failed with retryable error, retrying in {$waitTime}s...", [
                        'product_id' => $product->id,
                        'error' => $e->getMessage(),
                    ]);
                    sleep($waitTime);
                    continue;
                } else {
                    throw $e;
                }
            }
        }

        if ($response->successful()) {
            $product->update([
                'is_synced_to_whatsapp' => true,
                'whatsapp_synced_at' => now(),
                'whatsapp_sync_error' => null,
            ]);
            
            Log::info('Product updated successfully in WhatsApp catalog', [
                'product_id' => $product->id,
                'whatsapp_product_id' => $product->whatsapp_product_id,
            ]);
            
            return true;
        }

        $error = $this->extractErrorMessageFromResponse($response);
        throw new \Exception('Failed to update product: ' . $error);
    }

    /**
     * Build product payload for WhatsApp API
     */
    protected function buildProductPayload(Product $product): array
    {
        // Get first product image or use main image
        $imageUrl = null;
        if ($product->images->isNotEmpty()) {
            $firstImage = $product->images->first();
            if ($firstImage->path) {
                // Use url() to generate full HTTPS URL for external access
                $imageUrl = url('storage/' . $firstImage->path);
            }
        } elseif ($product->image) {
            // Use url() to generate full HTTPS URL for external access
            $imageUrl = url('storage/' . $product->image);
        }
        
        // Ensure image URL is publicly accessible HTTPS URL
        if ($imageUrl) {
            // Replace http:// with https:// if needed
            $imageUrl = str_replace('http://', 'https://', $imageUrl);
            
            // Validate it's a full URL
            if (!filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                // If not a valid URL, try generating full URL
                $imageUrl = url($imageUrl);
            }
        } else {
            // Use placeholder if no image - ensure it's a full HTTPS URL
            $imageUrl = url('images/placeholder.png');
            $imageUrl = str_replace('http://', 'https://', $imageUrl);
        }
        
        $currency = config('app.currency', 'USD');
        $price = $product->selling_price ?? $product->price ?? 0;

        // Limit description to 1000 characters (WhatsApp limit)
        $description = strip_tags($product->description ?? '');
        if (mb_strlen($description) > 1000) {
            $description = mb_substr($description, 0, 997) . '...';
        }

        $payload = [
            'name' => mb_substr($product->name, 0, 255), // Limit name length
            'description' => $description,
            'retailer_id' => (string) $product->id,
            'price' => (int) ($price * 100), // Price in smallest currency unit (cents/paisa)
            'currency' => strtoupper($currency),
            'image_url' => $imageUrl,
            'availability' => ($product->stock_quantity > 0 && $product->is_active) ? 'in stock' : 'out of stock',
        ];

        // Add category if available
        if ($product->category && $product->category->name) {
            $payload['category'] = mb_substr($product->category->name, 0, 255);
        }

        return $payload;
    }

    /**
     * Extract error message from exception or response
     */
    protected function extractErrorMessage(\Exception $e): string
    {
        $message = $e->getMessage();
        
        // Try to extract JSON error if present
        if (preg_match('/\{.*"error".*\}/', $message, $matches)) {
            $errorData = json_decode($matches[0], true);
            if (isset($errorData['error']['message'])) {
                return $errorData['error']['message'];
            }
        }
        
        return $message;
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
        
        // Check for timeout/connection errors in response
        $body = $response->body();
        if (stripos($body, 'timeout') !== false || 
            stripos($body, 'connection') !== false ||
            stripos($body, 'curl error 28') !== false ||
            stripos($body, 'curl error 56') !== false ||
            stripos($body, 'connection was reset') !== false ||
            stripos($body, 'recv failure') !== false) {
            return true;
        }
        
        return false;
    }

    /**
     * Check if an exception is retryable
     */
    protected function isRetryableException(\Exception $e): bool
    {
        $message = $e->getMessage();
        
        // Check for connection reset, timeout, and network errors
        $retryablePatterns = [
            'curl error 56',
            'curl error 28',
            'connection was reset',
            'recv failure',
            'connection timeout',
            'connection refused',
            'connection reset',
            'network',
            'timeout',
        ];
        
        foreach ($retryablePatterns as $pattern) {
            if (stripos($message, $pattern) !== false) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Extract error message from HTTP response
     */
    protected function extractErrorMessageFromResponse($response): string
    {
        try {
            $body = $response->json();
            
            if (isset($body['error'])) {
                $error = $body['error'];
                $message = $error['message'] ?? 'Unknown error';
                $code = $error['code'] ?? null;
                $type = $error['type'] ?? null;
                
                $errorMsg = $message;
                if ($code) {
                    $errorMsg .= " (Error Code: {$code}";
                    if ($type) {
                        $errorMsg .= ", Type: {$type}";
                    }
                    $errorMsg .= ")";
                }
                
                // Add subcode if available for more details
                if (isset($error['error_subcode'])) {
                    $errorMsg .= " [Subcode: {$error['error_subcode']}]";
                }
                
                return $errorMsg;
            }
        } catch (\Exception $e) {
            // If JSON parsing fails, return raw body
        }
        
        $rawBody = $response->body();
        if (strlen($rawBody) > 500) {
            $rawBody = substr($rawBody, 0, 497) . '...';
        }
        
        return 'API Error: ' . $rawBody;
    }

    /**
     * Sync multiple products
     */
    public function syncProducts(array $productIds): array
    {
        $results = [
            'success' => 0,
            'failed' => 0,
            'errors' => [],
        ];

        Log::info('Starting bulk product sync', [
            'product_count' => count($productIds),
        ]);

        foreach ($productIds as $productId) {
            $product = Product::find($productId);
            if (!$product) {
                $results['failed']++;
                $results['errors'][] = "Product #{$productId}: Product not found";
                continue;
            }

            if ($this->syncProduct($product)) {
                $results['success']++;
            } else {
                $results['failed']++;
                $errorMsg = $product->whatsapp_sync_error ?? 'Unknown error';
                $results['errors'][] = "Product #{$productId} ({$product->name}): {$errorMsg}";
            }
            
            // Small delay to avoid rate limiting
            usleep(500000); // 0.5 second delay
        }

        Log::info('Bulk product sync completed', [
            'success' => $results['success'],
            'failed' => $results['failed'],
        ]);

        return $results;
    }

    /**
     * Generate WhatsApp catalog link
     */
    public function getCatalogLink(): ?string
    {
        try {
            $config = $this->getConfig();
            
            if (empty($config['enabled']) || empty($config['phone_number_id'])) {
                return null;
            }

            // Try to get catalog, but don't fail the page if it doesn't work
            try {
                $catalogId = $this->getOrCreateCatalog($config);
                if (!$catalogId) {
                    return null;
                }
            } catch (\Exception $e) {
                // Log the error but don't fail the page
                Log::warning('Failed to get catalog for link generation', [
                    'error' => $e->getMessage(),
                ]);
                // Still return a basic WhatsApp link even if catalog fails
            }

            // Format phone number (remove any non-digit characters)
            $phoneNumber = preg_replace('/[^0-9]/', '', $config['phone_number_id']);
            
            // WhatsApp catalog link format
            return "https://wa.me/{$phoneNumber}?text=" . urlencode("View our catalog");
        } catch (\Exception $e) {
            Log::warning('Failed to generate WhatsApp catalog link', [
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Generate product link for WhatsApp
     */
    public function getProductLink(Product $product): ?string
    {
        if (!$product->whatsapp_product_id || !$product->is_synced_to_whatsapp) {
            return null;
        }

        $config = $this->getConfig();
        
        if (empty($config['phone_number_id'])) {
            return null;
        }

        // Format phone number (remove any non-digit characters)
        $phoneNumber = preg_replace('/[^0-9]/', '', $config['phone_number_id']);
        
        $message = "I'm interested in: {$product->name}";
        return "https://wa.me/{$phoneNumber}?text=" . urlencode($message);
    }
}

