<?php


namespace App\Tests\Unit;


use App\Singleton\CustomGuzzleWrapper;
use App\Tests\BaseTestCase;
use PHPUnit\Framework\TestCase;

class AuthenticationControllerTest extends BaseTestCase
{

    public function testLoginSuccess(){
        $data = [
            "email"=> "jpec@benkyou.fr",
            "password"=> "test"
        ];
        $response = CustomGuzzleWrapper::getInstance()->getClient()->post('/login', [
            'body' => json_encode($data)
        ]);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testLoginError(){
        $data = [
            "email"=> "jpec@benkyou.fr",
            "password"=> "tes"
        ];
        $response = CustomGuzzleWrapper::getInstance()->getClient()->post('/login', [
            'body' => json_encode($data)
        ]);
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testCreateUserSuccess(){
        $data = [
            "email"=> "snouf@benkyou.fr",
            "username"=> "snouf",
            "password"=> "test"
        ];
        $response = CustomGuzzleWrapper::getInstance()->getClient()->post('/register', [
            'body' => json_encode($data)
        ]);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testCreateUserDuplicatedUserError(){
        $data = [
            "email"=> "jpec@benkyou.fr",
            "username"=> "jpec",
            "password"=> "test"
        ];
        $response = CustomGuzzleWrapper::getInstance()->getClient()->post('/register', [
            'body' => json_encode($data)
        ]);
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testCreateUserMissingFieldError(){
        $data = [
            "username"=> "jpec2",
            "password"=> "test"
        ];
        $response = CustomGuzzleWrapper::getInstance()->getClient()->post('/register', [
            'body' => json_encode($data)
        ]);
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testCreateUserPasswordTooShortError(){
        $data = [
            "username"=> "jpec2",
            "password"=> "te"
        ];
        $response = CustomGuzzleWrapper::getInstance()->getClient()->post('/register', [
            'body' => json_encode($data)
        ]);
        $this->assertEquals(400, $response->getStatusCode());
    }
}