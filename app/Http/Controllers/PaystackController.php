<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PaystackService;
use Illuminate\Support\Facades\Log;
use App\Models\PaystackTransaction;

class PaystackController extends Controller
{
    protected $paystackService;

    public function __construct(PaystackService $paystackService)
    {
        $this->paystackService = $paystackService;
    }

    /**
     * Initialize a payment transaction
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function initializePayment(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'email' => 'required|email',
                'amount' => 'required|numeric',
            ]);

            // Initialize the payment
            $result = $this->paystackService->initializePayment([
                'email' => $request->email,
                'amount' => $request->amount * 100, // Convert to kobo
                'currency' => 'GHS',
                'reference' => 'ref_' . time(),
            ]);

            if ($result['success']) {
                // Log the payment initialization
                Log::info('Payment initialized successfully: ' . ($result['reference'] ?? 'unknown'));

                // Return the authorization URL
                return response()->json([
                    'status' => 'success',
                    'message' => 'Payment initialized successfully',
                    'authorization_url' => $result['data']['data']['authorization_url'] ?? null,
                    'reference' => $result['reference'] ?? null,
                ]);
            } else {
                // Log the error
                Log::error('Payment initialization failed: ' . $result['message']);

                // Return an error response
                return response()->json([
                    'status' => 'error',
                    'message' => 'Payment initialization failed: ' . $result['message'],
                ], 500);
            }

        } catch (\Exception $e) {
            // Log the error
            Log::error('Payment initialization failed: ' . $e->getMessage());

            // Return an error response
            return response()->json([
                'status' => 'error',
                'message' => 'Payment initialization failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show the payment form
     *
     * @return \Illuminate\View\View
     */
    public function showPaymentForm()
    {
        return view('paystack-payment');
    }

    /**
     * Fetch all transactions
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchTransactions()
    {
        try {
            // Fetch all transactions from the database
            $transactions = PaystackTransaction::orderBy('created_at', 'desc')->get();

            // Format the transactions for the frontend
            $formattedTransactions = $transactions->map(function ($transaction) {
                return [
                    'reference' => $transaction->reference,
                    'email' => $transaction->email,
                    'amount' => $transaction->amount,
                    'currency' => $transaction->currency,
                    'status' => $transaction->status,
                    'gateway_response' => $transaction->gateway_response,
                    'channel' => $transaction->channel,
                    'created_at' => $transaction->created_at,
                ];
            });

            // Return the formatted transactions as JSON
            return response()->json($formattedTransactions);
        } catch (\Exception $e) {
            // Log the error
            Log::error('Failed to fetch transactions: ' . $e->getMessage());

            // Return an error response
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch transactions: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Verify a payment transaction
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyPayment(Request $request)
    {
        try {
            // Get the reference from the request body
            $reference = $request->input('reference');

            // Validate the reference
            if (!$reference) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Reference is required',
                ], 400);
            }

            // Verify the payment
            $result = $this->paystackService->verifyPayment($reference);

            if ($result['success']) {
                // Log the payment verification
                Log::info('Payment verified successfully: ' . $reference);

                // Save the transaction to the database
                $transactionData = $result['data']['data'];
                  
                $createdTransaction = PaystackTransaction::create([
                    'reference' => $transactionData['reference'],
                    'email' => $transactionData['customer']['email'],
                    'amount' => $transactionData['amount'] / 100, // Convert from kobo to GHS
                    'currency' => $transactionData['currency'],
                    'status' => $transactionData['status'],
                    'gateway_response' => $transactionData['gateway_response'],
                    'channel' => $transactionData['channel'],
                    'authorization_code' => $transactionData['authorization']['authorization_code'] ?? null,
                    'card_type' => $transactionData['authorization']['card_type'] ?? null,
                    'last4' => $transactionData['authorization']['last4'] ?? null,
                    'exp_month' => $transactionData['authorization']['exp_month'] ?? null,
                    'exp_year' => $transactionData['authorization']['exp_year'] ?? null,
                    'bank' => $transactionData['authorization']['bank'] ?? null,
                    'country_code' => $transactionData['authorization']['country_code'] ?? null,
                    'brand' => $transactionData['authorization']['brand'] ?? null,
                    'metadata' => $transactionData['metadata'] ?? null,
                ]);

                // Return the payment details in a simplified format
                return response()->json([
                    'status' => 'success',
                    'message' => 'Payment verified successfully',
                    'data' => [
                        'reference' => $createdTransaction->reference,
                        'email' => $createdTransaction->email,
                        'amount' => $createdTransaction->amount,
                        'currency' => $createdTransaction->currency,
                        'status' => $createdTransaction->status,
                        'gateway_response' => $createdTransaction->gateway_response,
                        'channel' => $createdTransaction->channel,
                        'created_at' => $createdTransaction->created_at,
                    ],
                ]);
            } else {
                // Log the error
                Log::error('Payment verification failed: ' . $result['message']);

                // Return an error response
                return response()->json([
                    'status' => 'error',
                    'message' => 'Payment verification failed: ' . $result['message'],
                ], 500);
            }

        } catch (\Exception $e) {
            // Log the error
            Log::error('Payment verification failed: ' . $e->getMessage());

            // Return an error response
            return response()->json([
                'status' => 'error',
                'message' => 'Payment verification failed: ' . $e->getMessage(),
            ], 500);
        }
    }
}