<?php

namespace App\Entities;

class TransactionEntity
{
    public $id;

    public $userIdPayee;

    public $userIdPayer;

    public $amount;

    public $type;

    public function __construct(
        $id = null,
        $userIdPayee = null,
        $userIdPayer = null,
        $amount = null,
        $type = null
    ) {
        $this->id = $id;
        $this->userIdPayee = $userIdPayee;
        $this->userIdPayer = $userIdPayer;
        $this->amount = $amount;
        $this->type = $type;
    }
}
