<?php

namespace App\Interfaces\Services;

use App\Entities\TransactionEntity;

interface ITransactionService
{
    public function makeTransaction(TransactionEntity $transactionEntity);
    public function addFunds(TransactionEntity $transactionEntity);
}
