<?php


namespace App\Service;


use App\Entity\DTO\SRSReview;
use Doctrine\ORM\EntityManagerInterface;

class SrsCardService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function modifySrsCardsAccordingToReview(SRSReview $srsReview): array
    {
        $cardReviews = $srsReview->getCardReviews();
        $modifiedCards = [];
        foreach ($cardReviews as $cardReview){
            $card = $cardReview->getCard();
            $card->handleCardReview($cardReview->getErrorCount());
            $modifiedCards[] = $card;
        }
        $this->entityManager->flush();
        return $modifiedCards;
    }
}