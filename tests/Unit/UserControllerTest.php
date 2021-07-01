<?php


namespace App\Tests\Unit;


use App\Singleton\CustomGuzzleWrapper;
use App\Tests\BaseTestCase;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class UserControllerTest extends BaseTestCase
{

    public function testUnauthorizedAccess(){
        $response = CustomGuzzleWrapper::getInstance()->getClient()->get('/me');
        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testAuthorizedAccess(){
        $response = CustomGuzzleWrapper::getInstance()->getClient()->get('/me', [
            "headers" => [
                'Authorization'=> "Bearer JeSuisUnToken"
            ]
        ]);
        $this->assertEquals(200, $response->getStatusCode());
    }

}