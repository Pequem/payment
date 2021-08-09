<?php

namespace App\Interfaces\Repositories;

use App\Entities\UserEntity;

interface IUserRepository extends IBaseRepository
{
    // Type of users
    public const TYPE_DEFAULT = 'default';
    public const TYPE_STORE = 'store';
    public function persist($userId, UserEntity $data): UserEntity;
    public function getTypes(): array;
    public function get(int $userId): UserEntity;
}
