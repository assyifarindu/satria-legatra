<?php

namespace App\Mail\TSP;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubmitRequestDocument extends Mailable
{
    use Queueable, SerializesModels;
    public $details;
    /**
     * Create a new message instance.
     * @param array $details
     * @return void
     */

    public function __construct(array $details)
    {
        $this->details = $details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('admin.satria@patria.co.id')
            ->subject('Request Document')
            ->view('email.tsp.email_request_document')->with('data', $this->details);
    }
}
