<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherAwareInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity("email")
 */
#[ApiResource]
class User implements UserInterface, PasswordAuthenticatedUserInterface

{
    /**
     * @Groups({"default"})
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="array")
     */
    private array $roles = [];

    /**
     * @Groups({"default"})
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\Email()
     * @Assert\NotNull()
     */
    private $email;

    /**
     * @Assert\NotNull()
     * @Assert\Length(
     *      min = 3,
     *      max = 50,
     *      minMessage = "Your password must be at least {{ limit }} characters long",
     *      maxMessage = "Your password cannot be longer than {{ limit }} characters"
     * )
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $username;

    /**
     * @ORM\OneToMany(targetEntity=ApiToken::class, mappedBy="associatedUser", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $apiTokens;

    /**
     * @ORM\OneToMany(targetEntity=SRSCard::class, mappedBy="user", orphanRemoval=true)
     */
    private $srsCards;

    #[Pure] public function __construct()
    {
        $this->apiTokens = new ArrayCollection();
        $this->srsCards = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return Collection|ApiToken[]
     */
    public function getApiTokens(): Collection
    {
        return $this->apiTokens;
    }

    public function addApiToken(ApiToken $apiToken): self
    {
        if (!$this->apiTokens->contains($apiToken)) {
            $this->apiTokens[] = $apiToken;
            $apiToken->setAssociatedUser($this);
        }

        return $this;
    }

    public function removeApiToken(ApiToken $apiToken): self
    {
        if ($this->apiTokens->removeElement($apiToken)) {
            // set the owning side to null (unless already changed)
            if ($apiToken->getAssociatedUser() === $this) {
                $apiToken->setAssociatedUser(null);
            }
        }

        return $this;
    }

    public function getRoles()
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getPasswordHasherName(): ?string
    {
        //Use the default hasher
        //https://symfony.com/doc/current/security/named_hashers.html
        return null;
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
            $srsCard->setUser($this);
        }

        return $this;
    }

    public function removeSrsCard(SRSCard $srsCard): self
    {
        if ($this->srsCards->removeElement($srsCard)) {
            // set the owning side to null (unless already changed)
            if ($srsCard->getUser() === $this) {
                $srsCard->setUser(null);
            }
        }

        return $this;
    }
}
