<?php

namespace App\Interfaces\Services;

use App\Entities\UserBalanceEntity;

interface IUserBalanceService
{
    public function init($userId);
    public function add($userId, $amount);
    public function hasBalance($userId): bool;
    public function get($userId): UserBalanceEntity;
}
