<?php


namespace App\Tests\Unit\Controller;


use App\Singleton\CustomGuzzleWrapper;
use App\Tests\BaseTestCase;
use PHPUnit\Framework\TestCase;

class AuthenticationControllerTest extends BaseTestCase
{
    /**
     * @group login
     */
    public function testLoginSuccess(){
        $data = [
            "email"=> "jpec@benkyou.fr",
            "password"=> "test"
        ];
        $response = CustomGuzzleWrapper::getInstance()->getClient()->post('/login', [
            'body' => json_encode($data)
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $body = json_decode($response->getBody()->getContents(), true);
        $this->assertArrayHasKey("token", $body);
    }
    /**
     * @group login
     */
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
    /**
     * @group register
     */
    public function testCreateUserSuccess(){
        $data = [
            "email"=> "snouf57@benkyou.fr",
            "username"=> "snouf57",
            "password"=> "test"
        ];
        $response = CustomGuzzleWrapper::getInstance()->getClient()->post('/register', [
            'body' => json_encode($data)
        ]);
        $body = json_decode($response->getBody()->getContents(), true);
        $this->assertArrayHasKey("token", $body);
        $this->assertEquals(201, $response->getStatusCode());
    }
    /**
     * @group register
     */
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
    /**
     * @group register
     */
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
    /**
     * @group register
     */
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