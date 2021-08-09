<?php

namespace App\Entities;

class UserBalanceEntity
{
    public $id;

    public $userId;

    public $balance;

    public function __construct(
        $id = null,
        $userId = null,
        $balance = null
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->balance = $balance;
    }
}
