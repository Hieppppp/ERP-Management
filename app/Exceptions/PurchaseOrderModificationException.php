<?php

namespace App\Exceptions;

use Exception;

class PurchaseOrderModificationException extends Exception
{
    /**
     * Create a new exception instance.
     *
     * @param string $message
     * @param int $code
     * @param Exception|null $previous
     * @return void
     */
    public function __construct($message = null, $code = 0, Exception $previous = null)
    {
        if (is_null($message)) {
            $message = __('message.notUpdatePurchaseOrder');
        }
        parent::__construct($message, $code, $previous);
    }
}
