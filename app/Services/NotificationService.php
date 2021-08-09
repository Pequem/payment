<?php

namespace App\Services;

use App\Jobs\NotificationJob;
use App\Interfaces\Services\INotificationService;
use App\Interfaces\Repositories\IUserRepository;
use App\Entities\UserEntity;
use App\Entities\TransactionEntity;

class NotificationService implements INotificationService
{

    private $userRepository;

    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Check with user is a store thats need receiver notification
     *
     * @param UserEntity
     */
    private function needNotification(UserEntity $user)
    {
        return $user->type == $this->userRepository::TYPE_STORE;
    }

    /**
     * Send notification to store user
     *
     * @param TransactionEntity
     */
    public function notify(TransactionEntity $transaction): void
    {
        $payee = $this->userRepository->get($transaction->userIdPayee);

        if ($this->needNotification($payee)) {
            dispatch(new NotificationJob($transaction));
        }
    }
}
