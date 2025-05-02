<?php

namespace App\Exceptions;

use Exception;

abstract class AppException extends Exception
{
    protected $code = 500;
}
