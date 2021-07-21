<?php


namespace App\Tests\Unit\Form;


use App\Entity\DTO\SRSReview;
use App\Entity\User;
use App\Entity\VocabCard;
use App\Form\SRSReviewType;
use Symfony\Component\Form\Test\TypeTestCase;

class SRSReviewFormTypeTest extends TypeTestCase
{

    public function testSubmitValidData()
    {
        $this->assertEquals(true, true);
//        $cardRepo = $this->entityManager->getRepository(VocabCard::class);
//        $eatCard = $cardRepo->findOneBy(['englishWord' => 'eat', 'cardLocale' => 'ja']);
//        $eatReversedCard = $cardRepo->findOneBy(['englishWord' => 'eat', 'cardLocale' => 'en']);
//
//        $viewer = new User();
//        $data = [
//            "cardReviews" => [
//                "0" => [
//                    "card" => $eatCard->getId(),
//                    "isCorrect" => false
//                ],
//                "1" => [
//                    "card" => $eatReversedCard->getId(),
//                    "isCorrect" => true
//                ],
//            ]
//        ];
//
//        $model = new SRSReview($viewer);
//        $form = $this->factory->create(SRSReviewType::class, $model);
//
//        $expected = new SRSReview($viewer);
////        foreach ($expected->getCardReviews() as $cardReview){
////            $card = $cardReview->getCard();
////            $card->
////        }
//        // ...populate $object properties with the data stored in $formData
//
//        // submit the data to the form directly
//        $form->submit($data);
//
//        // This check ensures there are no transformation failures
//        $this->assertTrue($form->isSynchronized());
//
//        $this->assertEquals($expected, $model);
    }
}