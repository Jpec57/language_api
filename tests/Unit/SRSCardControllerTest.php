<?php


namespace App\Tests\Unit;


use App\DataFixtures\VocabCardFixtures;
use App\Entity\DTO\SRSCardReview;
use App\Entity\DTO\SRSReview;
use App\Entity\SRSCard;
use App\Entity\User;
use App\Entity\VocabCard;
use App\Enum\SRSLevelEnum;
use App\Service\SrsCardService;
use App\Singleton\CustomGuzzleWrapper;
use App\Tests\BaseTestCase;
use App\Trait\TestingTrait;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SRSCardControllerTest extends KernelTestCase
{
    const JPEC_TEST_TOKEN = "JeSuisUnToken";

    use TestingTrait;

    private EntityManagerInterface $entityManager;
    private SrsCardService $srsCardService;

//    public function test__construct(SrsCardService $srsCardService)
//    {
//        $this->srsCardService = $srsCardService;
//
//    }

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        echo "\nReloading database...\n";
        exec("php bin/console doctrine:fixtures:load --no-interaction --env=test");
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $this->srsCardService = $kernel->getContainer()
            ->get('App\Service\SrsCardService');
    }

    private function assertSRSCardsAreEquals(SRSCard $expectedCard, SRSCard $card){
        $this->assertEquals($expectedCard->getLevel(), $card->getLevel());
        $this->assertEquals($expectedCard->getCorrectCount(), $card->getCorrectCount());
        $this->assertEquals($expectedCard->getErrorCount(), $card->getErrorCount());

    }

    /**
     * @group review
     */
    public function testReviewCardSuccessAndLevelShouldNotGoBelowZero()
    {
        $cardRepo = $this->entityManager->getRepository(VocabCard::class);
        $userRepo = $this->entityManager->getRepository(User::class);
        $user = $userRepo->findOneBy(["email"=> "jpec@benkyou.fr"]);
        /** @var SRSCard $eatCard */
        $eatCard = $cardRepo->findOneBy(['englishWord' => 'eat', 'cardLocale' => 'en']);
        /** @var SRSCard $eatReversedCard */
        $eatReversedCard = $cardRepo->findOneBy(['englishWord' => 'eat', 'cardLocale' => 'ja']);
        $expectedEatCard = clone $eatCard;
        $expectedEatCard
            ->setCorrectCount(VocabCardFixtures::EAT_CARD_CORRECT_COUNT + 1)
            ->setErrorCount(VocabCardFixtures::EAT_CARD_ERROR_COUNT + 0)
            ->setLevel(VocabCardFixtures::EAT_CARD_LEVEL + 1)
        ;

        $expectedEatReversedCard = clone $eatReversedCard;
        $expectedEatReversedCard
            ->setCorrectCount(VocabCardFixtures::EAT_CARD_CORRECT_COUNT - 1)
            ->setErrorCount(VocabCardFixtures::EAT_CARD_ERROR_COUNT + 1)
            //SHOULD NOT GO BELOW ZERO
            ->setLevel(0)
        ;

        $review = new SRSReview($user);
        $review->setCardReviews([
            ((new SRSCardReview())->setCard($eatCard))->setIsCorrect(true),
            ((new SRSCardReview())->setCard($eatReversedCard))->setIsCorrect(false),
        ]);
        $this->srsCardService->modifySrsCardsAccordingToReview($review);

        $this->entityManager->refresh($eatCard);
        $this->entityManager->refresh($eatReversedCard);

        $this->assertSRSCardsAreEquals($expectedEatCard, $eatCard);
        $this->assertSRSCardsAreEquals($expectedEatReversedCard, $eatReversedCard);
    }

    /**
     * @group review
     */
    public function testShouldNotGoAboveMaxLevel()
    {
        $cardRepo = $this->entityManager->getRepository(VocabCard::class);
        $userRepo = $this->entityManager->getRepository(User::class);
        $user = $userRepo->findOneBy(["email"=> "jpec@benkyou.fr"]);
        /** @var SRSCard $eatCard */
        $eatCard = $cardRepo->findOneBy(['englishWord' => 'eat', 'cardLocale' => 'en']);
        $eatCard->setLevel(SRSLevelEnum::BURNED);
        $expectedEatCard = clone $eatCard;
        $expectedEatCard
            ->setCorrectCount(VocabCardFixtures::EAT_CARD_CORRECT_COUNT + 1)
            ->setErrorCount(VocabCardFixtures::EAT_CARD_ERROR_COUNT + 0)
            ->setLevel(SRSLevelEnum::BURNED)
        ;

        $review = new SRSReview($user);
        $review->setCardReviews([
            ((new SRSCardReview())->setCard($eatCard))->setIsCorrect(true),
        ]);
        $this->srsCardService->modifySrsCardsAccordingToReview($review);

        $this->entityManager->refresh($eatCard);
        $this->assertSRSCardsAreEquals($expectedEatCard, $eatCard);
    }
}