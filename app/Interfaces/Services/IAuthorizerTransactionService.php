<?php

namespace App\Interfaces\Services;

interface IAuthorizerTransactionService
{
    public function check(): bool;
}
