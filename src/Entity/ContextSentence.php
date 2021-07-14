<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ContextSentenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContextSentenceRepository::class)
 */
#[ApiResource]
class ContextSentence
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sentence;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $locale;

    /**
     * @ORM\ManyToMany(targetEntity=VocabCard::class, mappedBy="contextSentences")
     */
    private $vocabCards;

    public function __construct()
    {
        $this->vocabCards = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSentence(): ?string
    {
        return $this->sentence;
    }

    public function setSentence(string $sentence): self
    {
        $this->sentence = $sentence;

        return $this;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return Collection|VocabCard[]
     */
    public function getVocabCards(): Collection
    {
        return $this->vocabCards;
    }

    public function addVocabCard(VocabCard $vocabCard): self
    {
        if (!$this->vocabCards->contains($vocabCard)) {
            $this->vocabCards[] = $vocabCard;
            $vocabCard->addContextSentence($this);
        }

        return $this;
    }

    public function removeVocabCard(VocabCard $vocabCard): self
    {
        if ($this->vocabCards->removeElement($vocabCard)) {
            $vocabCard->removeContextSentence($this);
        }

        return $this;
    }
}
