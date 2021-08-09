<?php

namespace App\Interfaces\Repositories;

use App\Entities\TransactionEntity;

interface ITransactionRepository extends IBaseRepository
{
    // Types of transactions
    public const TYPE_DEFAULT = 'default';
    public const TYPE_REVERSAL = 'reversal';
    public const TYPE_ADD_FUNDS = 'add_funds';
    public function persist(TransactionEntity $data): TransactionEntity;
    public function get($transactionId): TransactionEntity;
    public function getLastByPayer($payerId): TransactionEntity;
    public function getAllByUser($userId): array;
}
