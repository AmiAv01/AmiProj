<?php

namespace App\Exceptions;

use Exception;

class InvalidInvoiceException extends Exception
{
    public function __construct(string $invoice)
    {
        parent::__construct("Invalid invoice format: {$invoice}", 400);
    }
}
