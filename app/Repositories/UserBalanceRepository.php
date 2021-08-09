<?php

namespace App\Repositories;

use App\Models\UserBalance;
use App\Interfaces\Repositories\IUserBalanceRepository;
use App\Exceptions\NotFoundEntityException;
use App\Entities\UserBalanceEntity;

/**
    * Repository for user balance
    *
    * @SuppressWarnings(PHPMD.StaticAccess)
    *
*/
class UserBalanceRepository extends BaseRepository implements IUserBalanceRepository
{
    /**
     * Save a user balance in database
     *
     * @param int $userId
     * @param UserBalanceEntity $data
     */
    public function persist($userBalanceId, UserBalanceEntity $data): UserBalanceEntity
    {
        $userBalance = new UserBalance();

        if ($userBalanceId) {
            $userBalance = UserBalance::find($userBalanceId);
            if (!$userBalance) {
                throw new NotFoundEntityException('User balance not found');
            }
        }

        $userBalance->fill([
            'user_id' => $data->userId,
            'balance' => $data->balance
        ]);

        $userBalance->save();

        $data->id = $userBalance->id;

        return $data;
    }

    /**
     * Get a user balance
     *
     * @param int $userBalanceId
     *
     * @return UserBalanceEntity $userData
     */
    public function get($userBalanceId): UserBalanceEntity
    {
        $userBalance = UserBalance::find($userBalanceId);

        if (!$userBalance) {
            throw new NotFoundEntityException('User balance not found');
        }

        return new UserBalanceEntity($userBalance->id, $userBalance->user_id, $userBalance->balance);
    }

    /**
     * Get a balance by user
     *
     * @param int $userId
     *
     * @return UserBalanceEntity
     */
    public function getByUser($userId): UserBalanceEntity
    {
        $userBalance = UserBalance::where('user_id', $userId)->first();

        if (!$userBalance) {
            throw new NotFoundEntityException('User balance not found');
        }

        return new UserBalanceEntity($userBalance->id, $userBalance->user_id, $userBalance->balance);
    }
}
