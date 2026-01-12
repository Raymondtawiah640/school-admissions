<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaystackService
{
    protected $secretKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->secretKey = env('PAYSTACK_SECRET_KEY');
        $this->baseUrl = env('PAYSTACK_PAYMENT_URL', 'https://api.paystack.co');
    }

    /**
     * Initialize a payment transaction
     *
     * @param array $data
     * @return array
     */
    public function initializePayment(array $data): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Content-Type' => 'application/json',
            ])->withoutVerifying()->post($this->baseUrl . '/transaction/initialize', $data);

            // Log the full response for debugging
            Log::info('Paystack initialization full response: ' . $response->body());

            if ($response->successful()) {
                $responseData = $response->json();
                
                // Enhanced logging to understand the actual response structure
                Log::info('Paystack initialization parsed response: ' . json_encode($responseData));
                
                // Log all available keys in the response
                if (is_array($responseData)) {
                    Log::info('Response data keys: ' . implode(', ', array_keys($responseData)));
                    if (isset($responseData['data'])) {
                        Log::info('Response data["data"] keys: ' . implode(', ', array_keys($responseData['data'])));
                    }
                }
                
                // Check if the response data contains the expected keys
                if (isset($responseData['data']) && isset($responseData['data']['reference'])) {
                    // Return the data in the expected structure
                    return [
                        'success' => true,
                        'data' => $responseData,
                        'reference' => $responseData['data']['reference'],
                    ];
                } elseif (isset($responseData['reference'])) {
                    // Alternative structure: reference might be at top level
                    Log::info('Found reference at top level: ' . $responseData['reference']);
                    return [
                        'success' => true,
                        'data' => $responseData,
                        'reference' => $responseData['reference'],
                    ];
                } else {
                    // Log the actual structure of the response data
                    Log::error('Invalid response data structure from Paystack: ' . json_encode($responseData));
                    return [
                        'success' => false,
                        'message' => 'Invalid response data from Paystack: missing reference key',
                    ];
                }
            } else {
                // Log the error response
                Log::error('Paystack initialization error response: ' . $response->body());
                return [
                    'success' => false,
                    'message' => 'Paystack API error: ' . $response->body(),
                ];
            }

        } catch (\Exception $e) {
            Log::error('Paystack payment initialization failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Payment initialization failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Verify a payment transaction
     *
     * @param string $reference
     * @return array
     */
    public function verifyPayment(string $reference): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Content-Type' => 'application/json',
            ])->withoutVerifying()->get($this->baseUrl . '/transaction/verify/' . $reference);

            if ($response->successful()) {
                $responseData = $response->json();
                return [
                    'success' => true,
                    'data' => $responseData,
                ];
            } else {
                return [
                    'success' => false,
                    'message' => $response->body(),
                ];
            }

        } catch (\Exception $e) {
            Log::error('Paystack payment verification failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Payment verification failed: ' . $e->getMessage(),
            ];
        }
    }
}