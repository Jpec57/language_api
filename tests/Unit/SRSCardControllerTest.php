<?php


namespace App\Tests\Unit;


use App\Entity\SRSCard;
use App\Singleton\CustomGuzzleWrapper;
use App\Tests\BaseTestCase;
use App\Trait\TestingTrait;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SRSCardControllerTest extends KernelTestCase
{
    const JPEC_TEST_TOKEN = "JeSuisUnToken";

    use TestingTrait;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    /**
     * @group review
     */
    public function testReviewCardSuccess()
    {
        $cardRepo = $this->entityManager->getRepository(SRSCard::class);
        $eatCard = $cardRepo->findOneBy(['type'=> 'vocab_card', 'englishWord' => 'eat', 'cardLocale' => 'ja']);
        $eatReversedCard = $cardRepo->findOneBy(['type'=> 'vocab_card', 'englishWord' => 'eat', 'cardLocale' => 'en']);
        $data = [
            "0" => [
                "cardId" => $eatCard->getId(),
                "isCorrect" => false
            ],
            "1" => [
                "cardId" => $eatReversedCard->getId(),
                "isCorrect" => true
            ],
        ];
        $response = CustomGuzzleWrapper::getInstance()->getClient()->post('/srs-cards/review/', [
            'body' => json_encode($data),
            "headers" => [
                'Authorization' => "Bearer " . self::JPEC_TEST_TOKEN
            ]
        ]);
        $body = json_decode($response->getBody()->getContents(), true);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArraysAreSimilar([
            "0" => [
                "id" => "57",
                "level" => 3-1,
                "nextAvailabilityDate" => null,
                "correctCount" => 0,
                "errorCount" => 0+1,
            ],
            "1" => [
                "id" => "58",
                "level" => 3+1,
                "nextAvailabilityDate" => null,
                "correctCount" => 0+1,
                "errorCount" => 0,
            ],
        ], $body[0]);
    }
}