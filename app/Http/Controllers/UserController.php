<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Exception;
use App\Interfaces\Services\IUserService;
use App\Interfaces\Services\IUserBalanceService;
use App\Exceptions\NotFoundEntityException;
use App\Entities\UserEntity;

class UserController extends Controller
{

    // Rules to validate the request
    private $validationRules;

    // Service to users
    private $userService;
    private $userBalanceService;

    public function __construct(
        IUserService $userService,
        IUserBalanceService $userBalanceService
    ) {
        $this->userService = $userService;
        $this->userBalanceService = $userBalanceService;

        // Fill user types availables
        $types = $this->userService->getTypes();
        $this->validationRules = [
            'first_name' => 'required|min:3|max:255',
            'last_name' => 'required|min:3|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|max:255',
            'cpf' => 'required_without:cnpj|cpf|unique:users',
            'cnpj' => 'required_without:cpf|cnpj|unique:users',
            'type' => 'required|in:' . implode(',', $types),
        ];
    }

    private function createUserEntity($userData)
    {
        return new UserEntity(
            null,
            $userData['first_name'],
            $userData['last_name'],
            $userData['cpf'] ?? null,
            $userData['cnpj'] ?? null,
            $userData['email'],
            $userData['type'],
            $userData['password']
        );
    }

    /**
     * Create a user
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $userData = $this->validate($request, $this->validationRules);

        try {
            $this->userService->create($this->createUserEntity($userData));
            return response('', 201);
        } catch (Exception $e) {
            return response($e->getMessage(), 400);
        }
    }

    /**
     * Update a user
     *
     * @return Response
     */
    public function update(Request $request, $userId)
    {
        $validationRules = $this->validationRules;

        $validationRules['cpf'] .= ',cpf,' . $userId;
        $validationRules['cnpj'] .= ',cnpj,' . $userId;
        $validationRules['email'] .= ',email,' . $userId;

        $userData = $this->validate($request, $validationRules);

        try {
            $this->userService->update($userId, $this->createUserEntity($userData));
            return response('', 200);
        } catch (NotFoundEntityException $e) {
            return response($e->getMessage(), 404);
        } catch (Exception $e) {
            return response($e->getMessage(), 500);
        }
    }

    /**
     * Get a user
     *
     * @return Response
     */
    public function get($userId)
    {
        try {
            $user = $this->userService->get($userId);
            if (!$user) {
                return response('', 404);
            }
            return response()->json($user);
        } catch (NotFoundEntityException $e) {
            return response($e->getMessage(), 404);
        } catch (Exception $e) {
            return response($e->getMessage(), 500);
        }
    }

    /**
     * Get user balance
     *
     * @return Response
     */
    public function getBalance($userId)
    {
        try {
            $balance = $this->userBalanceService->get($userId);
            if (!$balance) {
                return response('', 404);
            }
            return response()->json($balance);
        } catch (NotFoundEntityException $e) {
            return response($e->getMessage(), 404);
        } catch (Exception $e) {
            return response($e->getMessage(), 500);
        }
    }
}
