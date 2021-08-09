<?php

namespace App\Interfaces\Repositories;

use App\Entities\UserBalanceEntity;

interface IUserBalanceRepository extends IBaseRepository
{
    public function persist($userBalanceId, UserBalanceEntity $data): UserBalanceEntity;
    public function get($transactionId): UserBalanceEntity;
}
