<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\VocabCardRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=VocabCardRepository::class)
 */
#[ApiResource]
class VocabCard extends SRSCard
{

    /**
     * @Groups({"default"})
     * @ORM\Column(type="string", length=255)
     */
    private $wordToTranslate;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $englishWord;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="array", nullable=true)
     */
    private $alternativeWritings = [];

    /**
     * @Groups({"default"})
     * @ORM\Column(type="array", nullable=true)
     */
    private $synonyms = [];

    /**
     * @Groups({"default"})
     * @ORM\Column(type="array")
     */
    private $translations = [];

    /**
     * @Groups({"default"})
     * @ORM\Column(type="text", nullable=true)
     */
    private $userNotes;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="string", length=5)
     */
    private $translationLocale;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="string", length=5)
     */
    private $cardLocale;

    /**
     * @Groups({"default"})
     * @ORM\ManyToMany(targetEntity=LanguageLevel::class)
     */
    private $difficultyLevels;

    /**
     * @Groups({"default"})
     * @ORM\ManyToMany(targetEntity=ContextSentence::class, inversedBy="vocabCards")
     */
    private $contextSentences;

    public function __construct(User $user)
    {
        parent::__construct();
        $this->user = $user;
        $this->difficultyLevels = new ArrayCollection();
        $this->contextSentences = new ArrayCollection();
    }

    public function createReversedCard(): VocabCard
    {
        $reversedCard = new VocabCard($this->user);
        $translations = $this->getTranslations();
        if (count($translations) < 1){
            throw new \RuntimeException("You must have at least one translation to reverse your vocab card.");
        }
        $reversedTranslations = [$this->getWordToTranslate()];
        $synonyms = $this->getSynonyms();
        if ($synonyms){
            $reversedTranslations = array_merge($reversedTranslations, $synonyms);
        }
        if ($this->alternativeWritings){
            $reversedTranslations = array_merge($reversedTranslations, $this->alternativeWritings);
        }
        $reversedCard
//            ->setAlternativeWritings([])
            ->setEnglishWord($this->getEnglishWord())
            ->setWordToTranslate($translations[0])
            ->setTranslations($reversedTranslations)
            ->setSynonyms(array_slice($translations, 1))
            ->setCardLocale($this->getTranslationLocale())
            ->setTranslationLocale($this->getCardLocale())
            ->setUserNotes(null);
        return $reversedCard;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWordToTranslate(): ?string
    {
        return $this->wordToTranslate;
    }

    public function setWordToTranslate(string $wordToTranslate): self
    {
        $this->wordToTranslate = $wordToTranslate;

        return $this;
    }

    public function getEnglishWord(): ?string
    {
        return $this->englishWord;
    }

    public function setEnglishWord(?string $englishWord): self
    {
        $this->englishWord = $englishWord;

        return $this;
    }

    public function getAlternativeWritings(): ?array
    {
        return $this->alternativeWritings;
    }

    public function setAlternativeWritings(?array $alternativeWritings): self
    {
        $this->alternativeWritings = $alternativeWritings;

        return $this;
    }

    public function getSynonyms(): ?array
    {
        return $this->synonyms;
    }

    public function setSynonyms(?array $synonyms): self
    {
        $this->synonyms = $synonyms;

        return $this;
    }

    public function getTranslations(): ?array
    {
        return $this->translations;
    }

    public function setTranslations(array $translations): self
    {
        $this->translations = $translations;

        return $this;
    }

    public function getUserNotes(): ?string
    {
        return $this->userNotes;
    }

    public function setUserNotes(?string $userNotes): self
    {
        $this->userNotes = $userNotes;

        return $this;
    }

    public function getTranslationLocale(): ?string
    {
        return $this->translationLocale;
    }

    public function setTranslationLocale(string $translationLocale): self
    {
        $this->translationLocale = $translationLocale;

        return $this;
    }

    public function getCardLocale(): ?string
    {
        return $this->cardLocale;
    }

    public function setCardLocale(string $cardLocale): self
    {
        $this->cardLocale = $cardLocale;

        return $this;
    }

    /**
     * @return Collection|LanguageLevel[]
     */
    public function getDifficultyLevels(): Collection
    {
        return $this->difficultyLevels;
    }

    public function addDifficultyLevel(LanguageLevel $difficultyLevel): self
    {
        if (!$this->difficultyLevels->contains($difficultyLevel)) {
            $this->difficultyLevels[] = $difficultyLevel;
        }

        return $this;
    }

    public function removeDifficultyLevel(LanguageLevel $difficultyLevel): self
    {
        $this->difficultyLevels->removeElement($difficultyLevel);

        return $this;
    }

    /**
     * @return Collection|ContextSentence[]
     */
    public function getContextSentences(): Collection
    {
        return $this->contextSentences;
    }

    public function addContextSentence(ContextSentence $contextSentence): self
    {
        if (!$this->contextSentences->contains($contextSentence)) {
            $this->contextSentences[] = $contextSentence;
        }

        return $this;
    }

    public function removeContextSentence(ContextSentence $contextSentence): self
    {
        $this->contextSentences->removeElement($contextSentence);

        return $this;
    }
}
