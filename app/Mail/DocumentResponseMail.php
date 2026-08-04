<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DocumentResponseMail extends Mailable
{
    use Queueable, SerializesModels;
    public $requestDocumentQR;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($requestDocumentQR)
    {
        $this->requestDocumentQR = $requestDocumentQR;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Result Document Request Validation')->view('email.email_request_qr')->with('data', $this->requestDocumentQR);;
    }
}
