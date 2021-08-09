<?php

namespace App\Repositories;

use App\Models\Transaction;
use App\Interfaces\Repositories\ITransactionRepository;
use App\Exceptions\NotFoundEntityException;
use App\Entities\TransactionEntity;

/**
    * Repository for transaction
    *
    * @SuppressWarnings(PHPMD.StaticAccess)
    *
*/
class TransactionRepository extends BaseRepository implements ITransactionRepository
{
    /**
     * Save a transaction in database
     *
     * @param array $data
     */
    public function persist(TransactionEntity $transactionEntity): TransactionEntity
    {
        $transation = new Transaction(
            [
                'user_id_payee' => $transactionEntity->userIdPayee,
                'user_id_payer' => $transactionEntity->userIdPayer,
                'amount' => $transactionEntity->amount
            ]
        );

        $transation->save();

        $transactionEntity->id = $transation->id;

        return $transactionEntity;
    }

    /**
     * Get a user
     *
     * @param int $transactionId
     *
     * @return array $data
     */
    public function get($transactionId): TransactionEntity
    {
        $transaction = Transaction::find($transactionId);

        if (!$transaction) {
            throw new NotFoundEntityException('Transaction not found');
        }

        $transactionEntity = new TransactionEntity(
            $transaction->id,
            $transaction->user_id_payee,
            $transaction->user_id_payer,
            $transaction->amount
        );

        return $transactionEntity;
    }

    /**
     * Get last transaction by payer
     *
     * @param int $userId
     *
     * @return TransactionEntity
     */
    public function getLastByPayer($payerId): TransactionEntity
    {
        $transaction = Transaction::where('user_id_payer', $payerId)->orderBy('id', 'desc')->first();

        if (!$transaction) {
            throw new NotFoundEntityException('Transaction not found');
        }

        $transactionEntity = new TransactionEntity(
            $transaction->id,
            $transaction->user_id_payee,
            $transaction->user_id_payer,
            $transaction->amount
        );

        return $transactionEntity;
    }
}
