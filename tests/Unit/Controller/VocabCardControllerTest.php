<?php


namespace App\Tests\Unit\Controller;


use App\DataFixtures\UserFixtures;
use App\Singleton\CustomGuzzleWrapper;
use App\Tests\BaseTestCase;
use App\Traits\TestingTrait;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class VocabCardControllerTest extends BaseTestCase
{
    use TestingTrait;
    const CORRECT_CARD_BASE = [
        "wordToTranslate" => "toto",
        "englishWord" => "toto",
        "alternativeWritings" => [
            "とと",
            "トト"
        ],
        "synonyms" => [
            "たた"
        ],
        "translations" => [
            "string"
        ],
        "cardLocale" => "fr",
        "translationLocale" => "ja",
    ];

    /**
     * @group create-card
     */
    public function testCreateCardSuccess()
    {
        $data = self::CORRECT_CARD_BASE;
        $response = CustomGuzzleWrapper::getInstance()->getClient()->post('/vocab-cards/', [
            'body' => json_encode($data),
            "headers" => [
                'Authorization' => "Bearer " . UserFixtures::JPEC_TEST_TOKEN
            ]
        ]);

        $body = json_decode($response->getBody()->getContents(), true);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertCount(2, $body);
        $this->assertArraysAreSimilar([
            "wordToTranslate" => "toto",
            "alternativeWritings" => [
                "とと",
                "トト"
            ],
            "synonyms" => [
                "たた"
            ],
            "translations" => [
                "string"
            ],
            "cardLocale" => "fr",
            "translationLocale" => "ja",
            "level" => 0,
            "correctCount" => 0,
            "errorCount" => 0,
            "user" => [
                "email" => "jpec@benkyou.fr",
                "username" => "Jpec"
            ]
        ], $body[0]);

        $reversedCard = $body[1];

        $this->assertArraysAreSimilar([
            "wordToTranslate" => "string",
            "translations" => [
                "toto",
                "たた",
                "とと",
                "トト",
            ],
            "cardLocale" => "ja",
            "translationLocale" => "fr",
            "level" => 0,
            "correctCount" => 0,
            "errorCount" => 0,
            "user" => [
                "email" => "jpec@benkyou.fr",
                "username" => "Jpec"
            ]
        ], $reversedCard);
    }
}