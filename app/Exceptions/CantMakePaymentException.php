<?php

namespace App\Exceptions;

use Exception;

class CantMakePaymentException extends Exception
{
    public function __construct()
    {
        parent::__construct("This user can't make a payment");
    }
}
