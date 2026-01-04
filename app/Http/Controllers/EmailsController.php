<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Services\EmailService;

class EmailsController extends Controller
{
    protected $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * Send welcome email via API
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function welcomeEmail(Request $request)
    {
        try {
            // Get parameters from request or use defaults
            $email = $request->input('email', 'recipient@gmail.com');
            $subject = $request->input('subject', 'Welcome to School Management System');
            $content = $request->input('content', '<p>Welcome to School Management System</p>');
            
            Log::info("Attempting to send welcome email to: {$email}");
            
            $result = $this->emailService->sendWelcomeEmail($email, $subject, $content);
            
            if ($result['success']) {
                return response()->json([
                    'status' => 'success',
                    'message' => $result['message']
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => $result['message']
                ], 500);
            }
        } catch (Exception $e) {
            Log::error('Email sending failed: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Email sending failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
