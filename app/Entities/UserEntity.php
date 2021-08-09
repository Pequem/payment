<?php

namespace App\Entities;

class UserEntity
{
    public $id;

    public $firstName;

    public $lastName;

    public $cpf;

    public $cnpj;

    public $email;

    public $type;

    public $password;

    public function __construct(
        $id = null,
        $firstName = null,
        $lastName = null,
        $cpf = null,
        $cnpj = null,
        $email = null,
        $type = null,
        $password = null
    ) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->cpf = $cpf;
        $this->cnpj = $cnpj;
        $this->email = $email;
        $this->type = $type;
        $this->password = $password;
    }
}
