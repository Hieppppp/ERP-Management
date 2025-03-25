<?php
namespace App\Mail;

use App\Models\ReturnOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class ReturnOrderMail extends Mailable
{
    use Queueable, SerializesModels;
    public $returnOrder;

    /**
     * Create a new message instance.
     */
    public function __construct($returnOrder)
    {
        $this->returnOrder = $returnOrder;
    }

    public function build()
    {
        $pdf = Pdf::loadView('pages/return-order/invoice', $this->returnOrder);
    
        return $this->view('pages.return-order.mail', [
                'supplierName' => $this->returnOrder['returnOrder']->purchaseOrder->supplier->name,
                'orderNumber' => $this->returnOrder['returnOrder']->purchaseOrder->code
            ])
            ->subject(__('translation.returnOrder.newReturnOrder'))
            ->attachData($pdf->output(), 'return-order.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
