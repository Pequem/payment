<?php

namespace App\Http\Controllers;

use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Exception;
use App\Interfaces\Services\IUserService;
use App\Exceptions\NotFoundEntityException;
use App\Entities\UserEntity;

class UserController extends Controller
{

    // Rules to validate the request
    private $validationRules;

    // Service to users
    private $userService;

    public function __construct(IUserService $userService)
    {
        $this->userService = $userService;

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
        $userData = $request->validate($this->validationRules);

        try {
            $this->userService->update($userId, $this->createUserEntity($userData));
            return response('', 200);
        } catch (NotFoundEntityException $e) {
            return response($e->getMessage(), 400);
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
            return response($e->getMessage(), 400);
        } catch (Exception $e) {
            return response($e->getMessage(), 500);
        }
    }
}
