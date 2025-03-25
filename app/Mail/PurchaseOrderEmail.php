<?php

namespace App\Mail;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PurchaseOrderEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $purchaseOrder;
    /**
     * Create a new message instance.
     */
    public function __construct($purchaseOrder)
    {
        $this->purchaseOrder = $purchaseOrder;
    }

    public function build()
    {
        $pdf = Pdf::loadView('pages/purchase-order/invoice', $this->purchaseOrder);
        return $this->view('pages.purchase-order.mail', ['supplierName' => $this->purchaseOrder['purchaseOrder']->supplier->name])
            ->subject(__('translation.purchaseOrder.newPurchaseOrder'))
            ->attachData($pdf->output(), 'purchase_order.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
