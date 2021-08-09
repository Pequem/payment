<?php

namespace App\Jobs;

use GuzzleHttp\Client;
use Exception;
use App\Entities\TransactionEntity;

class NotificationJob extends Job
{

    private $transaction;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(TransactionEntity $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = new Client();
        $res = $client->post(
            'http://o4d9z.mocklab.io/notify'
        );
        if ($res->getStatusCode() != 201) {
            throw new Exception("Job fail");
        }
    }
}
