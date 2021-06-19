<?php

namespace App\Singleton;

use App\Trait\SingletonTrait;
use GuzzleHttp\Client;

class CustomGuzzleWrapper
{
    const API_URL = "http://localhost:8000";
    use SingletonTrait;
    private Client $client;

    private function __construct(){
        $this->client = new Client([
            'base_uri' => self::API_URL,
            'defaults' => [
                'exceptions' => false
            ],
            'http_errors'=> false,
            'verify' => false
        ]);
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }
}