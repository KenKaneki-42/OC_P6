<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: Types::INTEGER)]
  private ?int $id = null;

  #[ORM\Column(type: Types::STRING, length: 255, unique: true)]
  #[Assert\NotBlank]
  #[Assert\Length(min: 6, max: 100)]
  #[Assert\Email]
  private ?string $email = null;

  /**
   * @var string[]
   */
  #[ORM\Column(type: Types::JSON)]
  private array $roles = [];

  /**
   * @var string The hashed password
   */
  #[ORM\Column(type: Types::STRING, length: 255)]
  #[Assert\NotBlank]
  #[Assert\PasswordStrength(min: 8, minMessage: 'Your password must be at least {{ limit }} characters long', requireLetters: true, requireNumbers: true, requireCaseDiff: true, requireSpecialCharacter: true)]
  private ?string $password = null;

  #[ORM\OneToMany(targetEntity: Trick::class, mappedBy: 'user', orphanRemoval: true)]
  private Collection $tricks;

  #[ORM\Column(type: Types::STRING, length: 255)]
  #[Assert\NotBlank]
  private ?string $firstname = null;

  #[ORM\Column(length: 255)]
  #[Assert\NotBlank]
  private ?string $lastname = null;

  #[ORM\Column(length: 255, unique: true)]
  private ?string $token = null;

  #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
  #[Assert\NotBlank(message: 'creation date cannot be null')]
  private ?\DateTimeImmutable $createdAt = null;

  #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
  private ?\DateTimeImmutable $updatedAt = null;

  #[ORM\Column(type: Types::BOOLEAN, options: ['default' => false])]
  private ?bool $isEnabled = null;

  #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'user', orphanRemoval: true)]
  private Collection $comments;

  #[ORM\OneToMany(targetEntity: ProfilPicture::class, mappedBy: 'user')]
  private Collection $profilPictures;

  #[ORM\Column(type: Types::BOOLEAN, options: ['default' => false])]
  private bool $isVerified = false;

  public function __construct()
  {
    $this->tricks = new ArrayCollection();
    $this->comments = new ArrayCollection();
    $this->profilPictures = new ArrayCollection();
    $this->createdAt = new \DateTimeImmutable();
    $this->updatedAt = $this->createdAt;
    $this->isVerified = false;
    $this->isEnabled = true;
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getEmail(): ?string
  {
    return $this->email;
  }

  public function setEmail(string $email): static
  {
    $this->email = $email;

    return $this;
  }

  /**
   * A visual identifier that represents this user.
   *
   * @see UserInterface
   */
  public function getUserIdentifier(): string
  {
    return (string) $this->email;
  }

  /**
   * @see UserInterface
   *
   * @return list<string>
   */
  public function getRoles(): array
  {
    $roles = $this->roles;
    // guarantee every user at least has ROLE_USER
    $roles[] = 'ROLE_USER';

    return array_unique($roles);
  }

  /**
   * @param list<string> $roles
   */
  public function setRoles(array $roles): static
  {
    $this->roles = $roles;

    return $this;
  }

  /**
   * @see PasswordAuthenticatedUserInterface
   */
  public function getPassword(): string
  {
    return $this->password;
  }

  public function setPassword(string $password): static
  {
    $this->password = $password;

    return $this;
  }

  /**
   * @see UserInterface
   */
  public function eraseCredentials(): void
  {
    // If you store any temporary, sensitive data on the user, clear it here
    // $this->plainPassword = null;
  }

  /**
   * @return Collection<int, Trick>
   */
  public function getTricks(): Collection
  {
    return $this->tricks;
  }

  public function addTrick(Trick $trick): static
  {
    if (!$this->tricks->contains($trick)) {
      $this->tricks->add($trick);
      $trick->setUser($this);
    }

    return $this;
  }

  public function removeTrick(Trick $trick): static
  {
    if ($this->tricks->removeElement($trick)) {
      // set the owning side to null (unless already changed)
      if ($trick->getUser() === $this) {
        $trick->setUser(null);
      }
    }

    return $this;
  }

  public function getFirstname(): ?string
  {
    return $this->firstname;
  }

  public function setFirstname(string $firstname): static
  {
    $this->firstname = $firstname;

    return $this;
  }

  public function getLastname(): ?string
  {
    return $this->lastname;
  }

  public function setLastname(string $lastname): static
  {
    $this->lastname = $lastname;

    return $this;
  }

  public function getToken(): ?string
  {
    return $this->token;
  }

  public function setToken(string $token): static
  {
    $this->token = $token;

    return $this;
  }

  public function getCreatedAt(): ?\DateTimeImmutable
  {
    return $this->createdAt;
  }

  public function setCreatedAt(\DateTimeImmutable $createdAt): static
  {
    $this->createdAt = $createdAt;

    return $this;
  }

  public function getUpdatedAt(): ?\DateTimeImmutable
  {
    return $this->updatedAt;
  }

  public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
  {
    $this->updatedAt = $updatedAt;

    return $this;
  }

  public function getIsEnabled(): ?bool
  {
    return $this->isEnabled;
  }

  public function setIsEnabled(bool $isEnabled): static
  {
    $this->isEnabled = $isEnabled;

    return $this;
  }

  /**
   * @return Collection<int, Comment>
   */
  public function getComments(): Collection
  {
    return $this->comments;
  }

  public function addComment(Comment $comment): static
  {
    if (!$this->comments->contains($comment)) {
      $this->comments->add($comment);
      $comment->setUser($this);
    }

    return $this;
  }

  public function removeComment(Comment $comment): static
  {
    if ($this->comments->removeElement($comment)) {
      // set the owning side to null (unless already changed)
      if ($comment->getUser() === $this) {
        $comment->setUser(null);
      }
    }

    return $this;
  }

  /**
   * @return Collection<int, ProfilPicture>
   */
  public function getProfilPictures(): Collection
  {
    return $this->profilPictures;
  }

  public function addProfilPicture(ProfilPicture $profilPicture): static
  {
    if (!$this->profilPictures->contains($profilPicture)) {
      $this->profilPictures->add($profilPicture);
      $profilPicture->setUser($this);
    }

    return $this;
  }

  public function removeProfilPicture(ProfilPicture $profilPicture): static
  {
    if ($this->profilPictures->removeElement($profilPicture)) {
      // set the owning side to null (unless already changed)
      if ($profilPicture->getUser() === $this) {
        $profilPicture->setUser(null);
      }
    }

    return $this;
  }

  public function isVerified(): bool
  {
      return $this->isVerified;
  }

  public function setIsVerified(bool $isVerified): static
  {
      $this->isVerified = $isVerified;

      return $this;
  }
}
