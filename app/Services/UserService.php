<?php

namespace App\Services;

use Exception;
use App\Interfaces\Services\IUserService;
use App\Interfaces\Services\IUserBalanceService;
use App\Interfaces\Repositories\IUserRepository;
use App\Exceptions\NotFoundEntityException;
use App\Entities\UserEntity;

class UserService implements IUserService
{
    private $userRepository;
    private $userBalanceService;

    public function __construct(
        IUserRepository $userRepository,
        IUserBalanceService $userBalanceService
    ) {
        $this->userRepository = $userRepository;
        $this->userBalanceService = $userBalanceService;
    }

    /**
     * Get a user
     *
     * @param int $userId
     *
     * @return UserEntity $userData
     */
    public function get($userId): UserEntity
    {
        $user = $this->userRepository->get($userId);

        if (!$user) {
            throw new NotFoundEntityException('Cant found user');
        }

        return $user;
    }

    /**
     * update a user
     *
     * @param int $id
     * @param array $userData
     */
    public function update($userId, $data): void
    {
        $this->userRepository->persist($userId, $data);
    }

    /**
     * Create a user
     *
     * @param array $userData
     */
    public function create($data): void
    {
        try {
            $this->userRepository->beginTransaction();

            $user = $this->userRepository->persist(null, $data);
            $this->userBalanceService->init($user->id);

            $this->userRepository->commit();
        } catch (Exception $e) {
            $this->userRepository->rollback();
            throw $e;
        }
    }

    /**
     * Return user types
     *
     * @return array
     */
    public function getTypes(): array
    {
        return $this->userRepository->getTypes();
    }
}
