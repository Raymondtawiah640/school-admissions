<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\WelcomeMail;
use Exception;

class EmailService
{
    /**
     * Send a welcome email
     *
     * @param string $toEmail
     * @param string $subject
     * @param string $content
     * @return array
     */
    public function sendWelcomeEmail(string $toEmail, string $subject = 'Welcome', string $content = 'Welcome to our system'): array
    {
        try {
            Log::info("Sending welcome email to: {$toEmail}");
            
            // Clean up the content and build proper HTML
            $emailContent = $this->buildEmailContent($content);
            
            // Use the Mailable class directly with proper HTML
            Mail::to($toEmail)->send(new WelcomeMail($subject, $emailContent));
            
            Log::info("Email sent successfully to: {$toEmail}");
            
            return [
                'success' => true,
                'message' => 'Email sent successfully'
            ];
        } catch (Exception $e) {
            Log::error("Email sending failed: " . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Email sending failed: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Build email content from template or dynamic data
     */
    protected function buildEmailContent(string $content): string
    {
        // If content already has HTML tags, use it directly
        if (strpos($content, '<') !== false && strpos($content, '>') !== false) {
            return $content;
        }
        
        // Otherwise wrap it in basic HTML structure
        return "<html><body><p>{$content}</p></body></html>";
    }
}