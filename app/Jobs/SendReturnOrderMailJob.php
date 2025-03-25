<?php

namespace App\Jobs;

use App\Mail\ReturnOrderMail;
use App\Models\ReturnOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendReturnOrderMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $returnOrder;

    /**
     * Create a new job instance.
     */
    public function __construct($returnOrder)
    {
        $this->returnOrder = $returnOrder;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        Mail::to($this->returnOrder['returnOrder']->purchaseOrder->supplier->email)
            ->send(new ReturnOrderMail($this->returnOrder));
    }
}
