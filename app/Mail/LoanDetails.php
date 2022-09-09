<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoanDetails extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $data = [];
    public $pdfName;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $pdfName)
    {
        $this->data = $data;
        $this->pdfName = $pdfName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $return = $this->view('emails.loan_details')
        ->with($this->data);

        if($this->pdfName!='')
        $return->attach($this->pdfName, [
            'as' => 'name.pdf',
            'mime' => 'application/pdf',
        ]);

        return $return;
    }
}
