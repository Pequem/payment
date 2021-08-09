<?php

use Laravel\Lumen\Testing\DatabaseMigrations;

class UserIntegrationTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Create a valid user
     */
    public function testCreate()
    {
        $data = [
            'first_name' => 'Fernando',
            'last_name' => 'Machado',
            'cpf' => '08805064076',
            'email' => 'rodrigues.guerra@example.com',
            'password' => 'secret',
            'type' => 'default'
        ];

        $this->json('POST', '/user', $data)->seeStatusCode(201);

        unset($data['password']);

        $this->seeInDatabase('users', $data);
    }

    /**
     * Duplicate Email test
     */
    public function testDuplicateEmail()
    {
        $data = [
            'first_name' => 'Fernando',
            'last_name' => 'Machado',
            'cpf' => '08805064076',
            'email' => 'rodrigues.guerra@example.com',
            'password' => 'secret',
            'type' => 'default'
        ];

        $data2 = [
            'first_name' => 'Fernando',
            'last_name' => 'Machado',
            'cpf' => '73320572040',
            'email' => 'rodrigues.guerra@example.com',
            'password' => 'secret',
            'type' => 'default'
        ];

        $this->json('POST', '/user', $data)->seeStatusCode(201);
        $this->json('POST', '/user', $data)->seeStatusCode(422);
    }


    /**
     * Duplicate CPF test
     */
    public function testDuplicateCpf()
    {
        $data = [
            'first_name' => 'Fernando',
            'last_name' => 'Machado',
            'cpf' => '08805064076',
            'email' => 'rodrigues.guerra@example.com',
            'password' => 'secret',
            'type' => 'default'
        ];

        $data2 = [
            'first_name' => 'Fernando',
            'last_name' => 'Machado',
            'cpf' => '08805064076',
            'email' => 'rodrigues.guerra2@example.com',
            'password' => 'secret',
            'type' => 'default'
        ];

        $this->json('POST', '/user', $data)->seeStatusCode(201);
        $this->json('POST', '/user', $data2)->seeStatusCode(422);
    }

    /**
     * Create a valid user
     */
    public function testCreateStore()
    {
        $data = [
            'first_name' => 'Fernando',
            'last_name' => 'Machado',
            'cnpj' => '75850225000136',
            'email' => 'rodrigues.guerra@example.com',
            'password' => 'secret',
            'type' => 'store'
        ];

        $this->json('POST', '/user', $data)->seeStatusCode(201);

        unset($data['password']);

        $this->seeInDatabase('users', $data);
    }

    /**
     * Duplicate CNPJ
     */
    public function testDuplicateCNPJ()
    {
        $data = [
            'first_name' => 'Fernando',
            'last_name' => 'Machado',
            'cnpj' => '75850225000136',
            'email' => 'rodrigues.guerra@example.com',
            'password' => 'secret',
            'type' => 'store'
        ];

        $this->json('POST', '/user', $data)->seeStatusCode(201);

        $data = [
            'first_name' => 'Fernando',
            'last_name' => 'Machado',
            'cnpj' => '75850225000136',
            'email' => 'rodrigues.guerra2@example.com',
            'password' => 'secret',
            'type' => 'store'
        ];

        $this->json('POST', '/user', $data)->seeStatusCode(422);
    }

    /**
     * Invalid user type
     */
    public function testInvalidUserType()
    {
        $data = [
            'first_name' => 'Fernando',
            'last_name' => 'Machado',
            'cnpj' => '75850225000136',
            'email' => 'rodrigues.guerra2@example.com',
            'password' => 'secret',
            'type' => 'any'
        ];

        $this->json('POST', '/user', $data)->seeStatusCode(422);
    }
}
