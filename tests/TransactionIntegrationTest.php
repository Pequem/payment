<?php

use Laravel\Lumen\Testing\DatabaseMigrations;

class TransactionIntegrationTest extends TestCase
{
    use DatabaseMigrations;

   /**
    * Prepare users table
    */
    public function seedUsers()
    {
        $users = [];
        $users[] = [
            'first_name' => 'Fernando',
            'last_name' => 'Machado',
            'cpf' => '08805064076',
            'email' => 'rodrigues.guerra@example.com',
            'password' => 'secret',
            'type' => 'default'
        ];

        $users[] = [
            'first_name' => 'Example',
            'last_name' => 'Machado',
            'cpf' => '36164227011',
            'email' => 'example@example.com',
            'password' => 'secret',
            'type' => 'default'
        ];

        $users[] = [
            'first_name' => 'store1',
            'last_name' => 'Machado',
            'cnpj' => '75850225000136',
            'email' => 'example1@example.com',
            'password' => 'secret',
            'type' => 'store'
        ];

        $users[] = [
            'first_name' => 'store2',
            'last_name' => 'Machado',
            'cnpj' => '75850225000136',
            'email' => 'example2@example.com',
            'password' => 'secret',
            'type' => 'store'
        ];

        foreach ($users as $user) {
            $this->post('/user', $user);
        }
    }

    public function addFunds()
    {
        $funds[] = [
            'user_id' => 1,
            'amount' => 500
        ];
        $funds[] = [
            'user_id' => 2,
            'amount' => 500
        ];
        $funds[] = [
            'user_id' => 3,
            'amount' => 500
        ];
        $funds[] = [
            'user_id' => 4,
            'amount' => 500
        ];

        foreach ($funds as $fund) {
            $this->post('/transaction/funds', $fund);
        }
    }

    /**
     * Add funds test
     */
    public function testAddFunds()
    {
        $this->seedUsers();

        $data = [
            'user_id' => 1,
            'amount' => 500
        ];

        $this->post('/transaction/funds', $data)
            ->seeStatusCode(201);

        $this->seeInDatabase('user_balances', [
                'user_id' => $data['user_id'],
                'balance' => 500
            ]);
        $this->seeInDatabase('transactions', [
            'user_id_payee' => $data['user_id'],
            'user_id_payer' => null,
            'amount' => 500
        ]);
    }


    /**
     * Make transaction User to user
     */
    public function testTrasactionC2C()
    {
        $this->seedUsers();
        $this->addFunds();

        $this->post('/transaction', [
            'payer' => 1,
            'payee' => 2,
            'value' => 200
        ])->seeStatusCode(201);

        $this->seeInDatabase('transactions', [
            'user_id_payee' => 2,
            'user_id_payer' => 1,
            'amount' => 200
        ]);

        $this->seeInDatabase('user_balances', [
            'user_id' => 1,
            'balance' => 300
        ]);

        $this->seeInDatabase('user_balances', [
            'user_id' => 2,
            'balance' => 700
        ]);
    }

    /**
     * Make transaction User to user
     */
    public function testTrasactionC2CFloat()
    {
        $this->seedUsers();
        $this->addFunds();

        $this->post('/transaction', [
            'payer' => 1,
            'payee' => 2,
            'value' => 200.54
        ])->seeStatusCode(201);

        $this->seeInDatabase('transactions', [
            'user_id_payee' => 2,
            'user_id_payer' => 1,
            'amount' => 200.54
        ]);

        $this->seeInDatabase('user_balances', [
            'user_id' => 1,
            'balance' => 299.46
        ]);

        $this->seeInDatabase('user_balances', [
            'user_id' => 2,
            'balance' => 700.54
        ]);
    }

    /**
     * Make transaction User to store
     */
    public function testTrasactionC2B()
    {
        $this->seedUsers();
        $this->addFunds();

        $this->post('/transaction', [
            'payer' => 1,
            'payee' => 3,
            'value' => 200.54
        ])->seeStatusCode(201);

        $this->seeInDatabase('transactions', [
            'user_id_payee' => 3,
            'user_id_payer' => 1,
            'amount' => 200.54
        ]);

        $this->seeInDatabase('user_balances', [
            'user_id' => 1,
            'balance' => 299.46
        ]);

        $this->seeInDatabase('user_balances', [
            'user_id' => 3,
            'balance' => 700.54
        ]);
    }

    /**
     * Make transaction store to user
     */
    public function testTrasactionB2C()
    {
        $this->seedUsers();
        $this->addFunds();

        $this->post('/transaction', [
            'payer' => 3,
            'payee' => 1,
            'value' => 200.54
        ])->seeStatusCode(400);
    }

    /**
     * Make transaction without funds
     */
    public function testTrasactionWithoutFunds()
    {
        $this->seedUsers();
        $this->addFunds();

        $this->post('/transaction', [
            'payer' => 1,
            'payee' => 3,
            'value' => 600
        ])->seeStatusCode(400);
    }

    /**
     * Make transaction with negative value
     */
    public function testTrasactionWithNegativeValue()
    {
        $this->seedUsers();
        $this->addFunds();

        $this->post('/transaction', [
            'payer' => 1,
            'payee' => 3,
            'value' => -200
        ])->seeStatusCode(422);
    }
}
