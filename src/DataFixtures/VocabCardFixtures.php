<?php


namespace App\DataFixtures;


use App\Entity\User;
use App\Entity\VocabCard;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class VocabCardFixtures extends Fixture implements DependentFixtureInterface
{
    const CARD_JISHO = "jisho";
    const CARD_JISHO_REVERSED = "jisho_reversed";
    const CARD_EAT = "eat";
    const CARD_EAT_REVERSED = "eat_reversed";
    const CARD_SNOUF = "snouf1";
    const CARD_SNOUF2 = "snouf2";

    const EAT_CARD_LEVEL = 3;
    const EAT_CARD_ERROR_COUNT = 0;
    const EAT_CARD_CORRECT_COUNT = 1;

    public function generateBasicCards(ObjectManager $manager, User $user){
        for ($i = 0; $i < 10; $i++){
            $vocabCard = new VocabCard($user);
            $vocabCard
                ->setEnglishWord("basic$i")
                ->setWordToTranslate("basic")
                ->setAlternativeWritings([])
                ->setSynonyms([])
                ->setUserNotes("Basic $i")
                ->setTranslations(["食べる", "たべる"])
                ->setTranslationLocale("ja")
                ->setCardLocale("en")
                ->setIsActivated(false)
            ;
            $manager->persist($vocabCard);
            $this->addReference("basic$i", $vocabCard);
        }

    }

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        /** @var User $userJpec */
        $userJpec = $this->getReference(UserFixtures::USER_JPEC_REFERENCE);
        /** @var User $userSnouf */
        $userSnouf = $this->getReference(UserFixtures::USER_SNOUF_REFERENCE);
        $this->generateBasicCards($manager, $userJpec);

        $vocabCard = new VocabCard($userJpec);
        $vocabCard
            ->setEnglishWord("dictionary")
            ->setWordToTranslate("辞書")
            ->setAlternativeWritings(["じしょ","ジショ"])
            ->setSynonyms([])
            ->setUserNotes("This is my first vocab card.")
            ->setTranslations(["Dictionary"])
            ->setTranslationLocale("en")
            ->setCardLocale("ja")
            ->setErrorCount(2)
            ->setCorrectCount(5)
            ->setLevel(7)
            ->setNextAvailabilityDate(null)
        ;
        $manager->persist($vocabCard);
        $this->addReference(self::CARD_JISHO, $vocabCard);

        $reversedCard = $vocabCard->createReversedCard();
        $manager->persist($reversedCard);
        $this->addReference(self::CARD_JISHO_REVERSED, $reversedCard);

        //TODO Use Mocker
        $vocabCard = new VocabCard($userJpec);
        $vocabCard
            ->setEnglishWord("eat")
            ->setWordToTranslate("")
            ->setAlternativeWritings([])
            ->setSynonyms([])
            ->setUserNotes("This is my first vocab card.")
            ->setTranslations(["食べる", "たべる"])
            ->setTranslationLocale("ja")
            ->setCardLocale("en")
            ->setErrorCount(self::EAT_CARD_ERROR_COUNT)
            ->setCorrectCount(self::EAT_CARD_CORRECT_COUNT)
            ->setLevel(self::EAT_CARD_LEVEL)
            ->setNextAvailabilityDate(null)
        ;
        $manager->persist($vocabCard);
        $this->addReference(self::CARD_EAT, $vocabCard);
        $reversedCard = $vocabCard->createReversedCard();
        $manager->persist($reversedCard);
        $this->addReference(self::CARD_EAT_REVERSED, $reversedCard);


        $vocabCard = new VocabCard($userSnouf);
        $vocabCard
            ->setEnglishWord("snouf1")
            ->setWordToTranslate("")
            ->setAlternativeWritings([])
            ->setSynonyms([])
            ->setUserNotes("This is my first vocab card.")
            ->setTranslations(["食べる", "たべる"])
            ->setTranslationLocale("ja")
            ->setCardLocale("en")
            ->setNextAvailabilityDate(null)
        ;
        $manager->persist($vocabCard);
        $this->addReference(self::CARD_SNOUF, $vocabCard);

        $vocabCard = new VocabCard($userSnouf);
        $vocabCard
            ->setEnglishWord("snouf2")
            ->setWordToTranslate("")
            ->setAlternativeWritings([])
            ->setSynonyms([])
            ->setUserNotes("This is my first vocab card.")
            ->setTranslations(["食べる", "たべる"])
            ->setTranslationLocale("ja")
            ->setCardLocale("en")
            ->setNextAvailabilityDate(new \DateTime('+2 weeks'))
        ;
        $manager->persist($vocabCard);
        $this->addReference(self::CARD_SNOUF2, $vocabCard);

        $manager->flush();

    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}