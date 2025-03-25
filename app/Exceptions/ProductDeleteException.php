<?php

namespace App\Exceptions;

use Exception;

class ProductDeleteException extends Exception
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
            $message = __('message.notDeleteProduct');
        }
        parent::__construct($message, $code, $previous);
    }
}
