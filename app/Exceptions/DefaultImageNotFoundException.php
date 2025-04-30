<?php

namespace App\Exceptions;

use Exception;

class DefaultImageNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct("Default product image not found");
    }
}
