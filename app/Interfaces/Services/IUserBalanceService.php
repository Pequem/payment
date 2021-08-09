<?php

namespace App\Interfaces\Services;

interface IUserBalanceService
{
    public function init($userId);
    public function add($userId, $amount);
    public function hasBalance($userId): bool;
}
