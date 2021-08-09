<?php

namespace App\Entities;

class TransactionEntity
{
    public $id;

    public $userIdPayee;

    public $userIdPayer;

    public $amount;

    public function __construct(
        $id = null,
        $userIdPayee = null,
        $userIdPayer = null,
        $amount = null
    ) {
        $this->id = $id;
        $this->userIdPayee = $userIdPayee;
        $this->userIdPayer = $userIdPayer;
        $this->amount = $amount;
    }
}
