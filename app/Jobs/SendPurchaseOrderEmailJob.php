<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\PurchaseOrderEmail;

class SendPurchaseOrderEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $purchaseOrder;
    /**
     * Create a new job instance.
     */
    public function __construct($purchaseOrder)
    {
        $this->purchaseOrder = $purchaseOrder;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        Mail::to($this->purchaseOrder['purchaseOrder']->supplier->email)
            ->send(new PurchaseOrderEmail($this->purchaseOrder));
    }
}
