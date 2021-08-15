<?php


namespace App\Entity\DTO;

use App\Entity\SRSCard;

class SRSCardReview
{
    private SRSCard $card;

    private int $errorCount;

    public function __construct()
    {
        $this->errorCount = 0;
    }

    /**
     * @return int
     */
    public function getErrorCount(): int
    {
        return $this->errorCount;
    }

    /**
     * @param int $errorCount
     * @return SRSCardReview
     */
    public function setErrorCount($errorCount): SRSCardReview
    {
        $this->errorCount = $errorCount;
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