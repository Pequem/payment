<?php

namespace App\Repositories;

use App\Interfaces\Repositories\IBaseRepository;
use Illuminate\Support\Facades\DB;

/**
    * Base repository
    *
    * @SuppressWarnings(PHPMD.StaticAccess)
    *
    * @return Response
*/
class BaseRepository implements IBaseRepository
{
    public function beginTransaction()
    {
        DB::beginTransaction();
    }

    public function commit()
    {
        DB::commit();
    }

    public function rollback()
    {
        DB::rollback();
    }
}
