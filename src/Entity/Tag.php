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

    /**
     * @var string
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     */
    private $locale1;

    /**
     * @var string
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     */
    private $locale2;

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

    /**
     * @return string
     */
    public function getLocale1()
    {
        return $this->locale1;
    }

    /**
     * @param string $locale1
     * @return Tag
     */
    public function setLocale1($locale1): Tag
    {
        $this->locale1 = $locale1;
        return $this;
    }

    /**
     * @return string
     */
    public function getLocale2()
    {
        return $this->locale2;
    }

    /**
     * @param string $locale2
     * @return Tag
     */
    public function setLocale2($locale2): Tag
    {
        $this->locale2 = $locale2;
        return $this;
    }
}
