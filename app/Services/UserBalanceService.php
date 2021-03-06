<?php

namespace App\Services;

use App\Interfaces\Services\IUserBalanceService;
use App\Interfaces\Repositories\IUserBalanceRepository;
use App\Entities\UserBalanceEntity;

class UserBalanceService implements IUserBalanceService
{
    private $userBalanceRepository;

    public function __construct(
        IUserBalanceRepository $userBalanceRepository
    ) {
        $this->userBalanceRepository = $userBalanceRepository;
    }

    /**
     * Create a user balance
     *
     * @param int $userId
     */
    public function init($userId)
    {
        $this->userBalanceRepository->persist(
            null,
            new UserBalanceEntity(
                null,
                $userId,
                0
            )
        );
    }

    /**
     * Add funds in user balance
     *
     * @param int $userId
     * @param float $amount
     */
    public function add($userId, $amount)
    {
        $balance = $this->userBalanceRepository->getByUser($userId);

        $this->userBalanceRepository->persist(
            $balance->id,
            new UserBalanceEntity(
                null,
                $userId,
                $balance->balance + $amount
            )
        );
    }

    /**
     * Verify with a user has funds, balance > 0
     *
     * @param $userId
     *
     * @return bool
     */
    public function hasBalance($userId): bool
    {
        $balance = $this->userBalanceRepository->getByUser($userId);

        if ($balance->balance >= 0) {
            return true;
        }

        return false;
    }

    /**
     * Return balance of a user
     *
     * @param $userId
     *
     * @return UserBalanceEntity
     */
    public function get($userId): UserBalanceEntity
    {
        return $this->userBalanceRepository->getByUser($userId);
    }
}
