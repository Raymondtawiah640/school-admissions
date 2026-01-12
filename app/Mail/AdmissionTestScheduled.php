<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdmissionTestScheduled extends Mailable
{
    use Queueable, SerializesModels;

    public $admission;
    public $testDetails;

   
    public function __construct($admission, $testDetails)
    {
        $this->admission = $admission;
        $this->testDetails = $testDetails;
    }

   
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Admission Test Scheduled',
        );
    }

    
    public function content(): Content
    {
        return new Content(
            view: 'emails.admission_test_scheduled', // <- this is your view file
        );
    }

  
    public function attachments(): array
    {
        return [];
    }
}
