<?php

namespace App\Mail;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;

    /**
     * Create a new message instance.
     */
    public function __construct($invoice)
    {
        $this->invoice = $invoice;
    }

    public function build()
    {
        $pdf = Pdf::loadView('pages/invoice/pdf', $this->invoice);
        return $this->view('pages.invoice.mail', $this->invoice)
            ->subject(__('translation.invoice.invoice'))
            ->attachData($pdf->output(), 'Invoice.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
