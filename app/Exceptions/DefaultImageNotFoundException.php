<?php

namespace App\Exceptions;

class DefaultImageNotFoundException extends AppException
{
    public function __construct()
    {
        parent::__construct('Default product image not found');
    }
}
