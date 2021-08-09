<?php

namespace App\Interfaces\Repositories;

interface IBaseRepository
{
    public function beginTransaction();
    public function commit();
    public function rollback();
}
