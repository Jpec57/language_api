<?php


namespace App\Entity\DTO;

use App\Entity\SRSCard;

class SRSCardReview
{
    private SRSCard $card;

    private ?bool $isCorrect;

    public function __construct()
    {
        $this->isCorrect = false;
    }

    /**
     * @return bool
     */
    public function isCorrect(): bool
    {
        return $this->isCorrect;
    }

    /**
     * @param bool $isCorrect
     */
    public function setIsCorrect($isCorrect): self
    {
        $this->isCorrect = $isCorrect;
        return $this;
    }

    /**
     * @return SRSCard
     */
    public function getCard(): SRSCard
    {
        return $this->card;
    }

    /**
     * @param SRSCard $card
     * @return SRSCardReview
     */
    public function setCard(SRSCard $card): self
    {
        $this->card = $card;
        return $this;
    }
}