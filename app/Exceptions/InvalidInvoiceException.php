<?php

namespace App\Exceptions;

class InvalidInvoiceException extends ValidationException
{
    public function __construct(string $invoice)
    {
        parent::__construct("Invalid invoice format: {$invoice}");
    }
}
