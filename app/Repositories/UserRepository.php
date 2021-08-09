<?php

namespace App\Repositories;

use App\Entities\UserEntity;
use App\Exceptions\NotFoundEntityException;
use App\Interfaces\Repositories\IUserRepository;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
    * Repository for user
    *
    * @SuppressWarnings(PHPMD.StaticAccess)
    *
*/
class UserRepository extends BaseRepository implements IUserRepository
{
    /**
     * Save a user in database
     *
     * @param int $userId
     * @param array $data
     */
    public function persist($userId, UserEntity $data): UserEntity
    {
        $user = new User();

        if ($userId) {
            $user = User::find($userId);
            if (!$user) {
                throw new NotFoundEntityException('User not found');
            }
        }

        $user->fill([
            'email' => $data->email,
            'first_name' => $data->firstName,
            'last_name' => $data->lastName,
            'cpf' => $data->cpf,
            'cnpj' => $data->cnpj,
            'type' => $data->type
        ]);

        if ($data->password) {
            $user->password = Hash::make($data->password);
        }

        $user->save();

        $data->id = $user->id;

        return $data;
    }

    /**
     * Get a user
     *
     * @param int $userId
     *
     * @return array $userData
     */
    public function get(int $userId): UserEntity
    {
        $user = User::find($userId);

        if (!$user) {
            throw new NotFoundEntityException('User not found');
        }

        return new UserEntity(
            $user->id,
            $user->first_name,
            $user->last_name,
            $user->cpf,
            $user->cnpj,
            $user->email,
            $user->type,
            ''
        );
    }

    /**
     * Return types from model
     *
     * @return array
     */
    public function getTypes(): array
    {
        return [IUserRepository::TYPE_DEFAULT, IUserRepository::TYPE_STORE];
    }
}
