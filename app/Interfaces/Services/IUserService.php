<?php

namespace App\Interfaces\Services;

use App\Entities\UserEntity;

interface IUserService
{
    public function create(UserEntity $data): void;
    public function update(int $userId, UserEntity $data): void;
    public function getTypes(): array;
    public function get($userId): UserEntity;
}
