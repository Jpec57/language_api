<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Enum\SRSLevelEnum;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

///** @ORM\MappedSuperclass */
/**
 * @ORM\Entity
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"vocab_card" = "VocabCard"})
 */
abstract class SRSCard
{
    /**
     * @Groups({"default"})
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="boolean", options={"default":"1"})
     */
    protected $isActivated = true;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="integer")
     */
    protected $level;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $nextAvailabilityDate;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="integer")
     */
    protected $correctCount;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="integer")
     */
    protected $errorCount;

    /**
     * @Groups({"srscard_user"})
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="srsCards")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $user;

    /**
     * @Groups({"srscard_tag"})
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="srsCards")
     */
    protected $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->level = 0;
        $this->correctCount = 0;
        $this->errorCount = 0;
        $this->isActivated = true;
        $this->nextAvailabilityDate = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getNextAvailabilityDate(): ?\DateTimeInterface
    {
        return $this->nextAvailabilityDate;
    }

    public function setNextAvailabilityDate(?\DateTimeInterface $nextAvailabilityDate): self
    {
        $this->nextAvailabilityDate = $nextAvailabilityDate;

        return $this;
    }

    public function getCorrectCount(): ?int
    {
        return $this->correctCount;
    }

    public function setCorrectCount(int $correctCount): self
    {
        $this->correctCount = $correctCount;

        return $this;
    }

    public function getErrorCount(): ?int
    {
        return $this->errorCount;
    }

    public function setErrorCount(int $errorCount): self
    {
        $this->errorCount = $errorCount;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @return Collection|Tag[]
     */
    public function setTags($tags): self
    {
        $this->tags = $tags;
        return $this;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tags->removeElement($tag);
        return $this;
    }

    /**
     * @return bool
     */
    public function getIsActivated()
    {
        return $this->isActivated;
    }

    /**
     * @param bool $isActivated
     * @return SRSCard
     */
    public function setIsActivated($isActivated)
    {
        $this->isActivated = $isActivated;
        return $this;
    }

    public function handleCardReview(int $errorCount){
        $isSuccess = $errorCount == 0;
        $level = $this->level + ($isSuccess ? 1 : -1);
        if ($level >= SRSLevelEnum::BURNED){
            $level = SRSLevelEnum::BURNED;
        } else if ($level <= SRSLevelEnum::NEW){
            $level = SRSLevelEnum::NEW;
        }
        $this->level = $level;
        $diff = SRSLevelEnum::getDateIntervalDifferenceAccordingToLevel($level);
        if ($isSuccess){
            $this->correctCount += 1;
        } else {
            $this->errorCount += 1;
        }
        $this->nextAvailabilityDate = (new \DateTime())->modify($diff);
    }
}
