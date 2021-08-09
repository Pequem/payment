<?php

namespace App\Services;

use App\Repositories\TransactionRepository;
use App\Interfaces\Services\IUserBalanceService;
use App\Interfaces\Services\ITransactionService;
use App\Interfaces\Services\INotificationService;
use App\Interfaces\Services\IAuthorizerTransactionService;
use App\Interfaces\Repositories\IUserRepository;
use App\Interfaces\Repositories\ITransactionRepository;
use App\Exceptions\TransactionDeniedException;
use App\Exceptions\NotFoundEntityException;
use App\Exceptions\DuplicatedPaymentException;
use App\Exceptions\CantMakePaymentException;
use App\Entities\UserEntity;
use App\Entities\TransactionEntity;

class TransactionService implements ITransactionService
{
    private $userRepository;
    private $transactionRepository;
    private $userBalanceService;
    private $authorizerTransactionService;
    private $notificationService;

    public function __construct(
        IUserRepository $userRepository,
        ITransactionRepository $transactionRepository,
        IUserBalanceService $userBalanceService,
        IAuthorizerTransactionService $authorizerTransactionService,
        INotificationService $notificationService
    ) {
        $this->userRepository = $userRepository;
        $this->transactionRepository = $transactionRepository;
        $this->userBalanceService = $userBalanceService;
        $this->authorizerTransactionService = $authorizerTransactionService;
        $this->notificationService = $notificationService;
    }

    /**
     * Verify with user can make a transaction
     *
     * @param UserEntity
     *
     * @return bool
     */
    private function canMake(UserEntity $user)
    {
        return $user->type == IUserRepository::TYPE_DEFAULT;
    }

    /**
     * Verify with the trasaction is duplicate
     *
     * @param TransactionEntity
     *
     * @return bool
     */
    private function verifyDuplicity(TransactionEntity $transactionEntity): bool
    {
        try {
            $transaction = $this->transactionRepository->getLastByPayer($transactionEntity->userIdPayer);
            if (
                $transaction->userIdPayer == $transactionEntity->userIdPayer &&
                $transaction->userIdPayee == $transactionEntity->userIdPayee &&
                $transaction->amount == $transactionEntity->amount
            ) {
                return true;
            }

            return false;
        } catch (NotFoundEntityException $e) {
            return false;
        }
    }

    /**
     * Register a transaction in database
     *
     * @param UserEntity $payee
     * @param UserEntity $payer
     *
     * @param float $amount
     */
    private function registrerTransaction(UserEntity $payee, UserEntity $payer, $amount)
    {
        $this->userBalanceService->add($payee->id, $amount);
        $this->userBalanceService->add($payer->id, -$amount);

        return $this->transactionRepository->persist(
            new TransactionEntity(
                null,
                $payee->id,
                $payer->id,
                $amount,
                TransactionRepository::TYPE_DEFAULT
            )
        );
    }

    /**
     * Make transaction between users
     *
     * @param TrasactionEntity
     */
    public function makeTransaction(TransactionEntity $transactionEntity)
    {
        $payee = $this->userRepository->get($transactionEntity->userIdPayee);
        $payer = $this->userRepository->get($transactionEntity->userIdPayer);

        if (!$this->canMake($payer)) {
            throw new CantMakePaymentException();
        }

        if ($this->verifyDuplicity($transactionEntity)) {
            throw new DuplicatedPaymentException("Cant make two payment equals");
        }

        if (!$this->authorizerTransactionService->check()) {
            throw new TransactionDeniedException("Transaction denied by external service");
        }

        try {
            $this->transactionRepository->beginTransaction();

            $transaction = $this->registrerTransaction($payee, $payer, $transactionEntity->amount);

            // Aqui eu verifico o saldo depois, para evitar o caso de aparecer outra transferencia
            // antes dessa atualizar o saldo e o usuário não ter saldo para as duas
            // transferencias
            if (!$this->userBalanceService->hasBalance($payer->id)) {
                throw new TransactionDeniedException('There is no balance');
            }

            $this->notificationService->notify($transaction);

            $this->transactionRepository->commit();
        } catch (\Exception $e) {
            $this->transactionRepository->rollback();
            throw $e;
        }
    }

    /**
     * Add funds in user account
     *
     * @param TransactionEntity
     */
    public function addFunds(TransactionEntity $transactionEntity)
    {
        $payee = $this->userRepository->get($transactionEntity->userIdPayee);

        try {
            $this->transactionRepository->beginTransaction();


            $this->userBalanceService->add($payee->id, $transactionEntity->amount);

            $this->transactionRepository->persist(
                new TransactionEntity(
                    null,
                    $payee->id,
                    null,
                    $transactionEntity->amount,
                    TransactionRepository::TYPE_ADD_FUNDS
                )
            );

            $this->transactionRepository->commit();
        } catch (\Exception $e) {
            $this->transactionRepository->rollback();
            throw $e;
        }
    }

    /**
     * Get user transaction history
     *
     * @param int $userId
     *
     * @return array
     */
    public function getAllByUser($userId): array
    {
        return $this->transactionRepository->getAllByUser($userId);
    }
}
