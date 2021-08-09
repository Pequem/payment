<?php

namespace App\Services;

use GuzzleHttp\Client;
use App\Interfaces\Services\IAuthorizerTransactionService;

class AuthorizerTransactionService implements IAuthorizerTransactionService
{
    private $client;
    private const EXTERNAL_URL = 'https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6';

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * Check with the transaction can be make
     *
     * @return bool
     */
    public function check(): bool
    {
        $response = $this->client->get(self::EXTERNAL_URL);

        if ($response->getStatusCode() == 200) {
            $body = $response->getBody()->getContents();
            $object = json_decode($body);
            if ($object && isset($object->message) && $object->message == 'Autorizado') {
                return true;
            }
        }

        return false;
    }
}
