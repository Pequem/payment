<?php

namespace App\Interfaces\Services;

use App\Entities\TransactionEntity;

interface INotificationService
{
    public function notify(TransactionEntity $transaction): void;
}
