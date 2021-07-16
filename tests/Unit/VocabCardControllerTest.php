<?php


namespace App\Tests\Unit;


use App\Singleton\CustomGuzzleWrapper;
use App\Tests\BaseTestCase;
use PHPUnit\Framework\TestCase;

class VocabCardControllerTest extends BaseTestCase
{
    /**
     * @group create-card
     */
    public function testCreateCardSuccess()
    {
        $data = [
            "wordToTranslate" => "toto",
            "englishWord" => "toto",
            "alternativeWritings" => [
                "とと"
            ],
            "synonyms" => [
                "たた"
            ],
            "translations" => [
                "string"
            ],
            "cardLocale" => "fr",
            "translationLocale" => "jp",
        ];
        $response = CustomGuzzleWrapper::getInstance()->getClient()->post('/vocab-cards', [
            'body' => json_encode($data),
            "headers" => [
                'Authorization'=> "Bearer JeSuisUnToken"
            ]
        ]);
        $body = json_decode($response->getBody()->getContents(), true);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertCount(2, $body);
        //TODO Assert inversion is OK
        $this->assertArrayHasKey("id", $body[0]);
        $this->assertEquals("toto", $body[0]["wordToTranslate"]);

        $this->assertArrayHasKey("id", $body[1]);
        $this->assertEquals("string", $body[1]["wordToTranslate"]);
    }
}