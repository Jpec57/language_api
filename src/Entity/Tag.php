<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TagRepository::class)
 */
class Tag
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"default"})
     */
    private $id;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $color;

    /**
     * @ORM\ManyToMany(targetEntity=SRSCard::class, mappedBy="tags")
     */
    private $srsCards;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="tags")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;
    /**
     * @Groups({"default"})
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastUseDate;

    public function __construct()
    {
        $this->srsCards = new ArrayCollection();
        $this->lastUseDate = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return Collection|SRSCard[]
     */
    public function getSrsCards(): Collection
    {
        return $this->srsCards;
    }

    public function addSrsCard(SRSCard $srsCard): self
    {
        if (!$this->srsCards->contains($srsCard)) {
            $this->srsCards[] = $srsCard;
            $srsCard->addTag($this);
        }

        return $this;
    }

    public function removeSrsCard(SRSCard $srsCard): self
    {
        if ($this->srsCards->removeElement($srsCard)) {
            $srsCard->removeTag($this);
        }

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
     * @return \DateTime
     */
    public function getLastUseDate()
    {
        return $this->lastUseDate;
    }

    /**
     * @param \DateTime $lastUseDate
     * @return Tag
     */
    public function setLastUseDate($lastUseDate): Tag
    {
        $this->lastUseDate = $lastUseDate;
        return $this;
    }
}
