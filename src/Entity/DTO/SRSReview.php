<?php


namespace App\Entity\DTO;


use App\Entity\User;

class SRSReview
{

    private User $user;

    /** @var SRSCardReview[] */
    private array $cardReviews;

    /**
     * SRSReview constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->cardReviews = [];
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return SRSCardReview[]
     */
    public function getCardReviews(): array
    {
        return $this->cardReviews;
    }

    /**
     * @param SRSCardReview[] $cardReviews
     */
    public function setCardReviews(array $cardReviews): void
    {
        $this->cardReviews = $cardReviews;
    }
}