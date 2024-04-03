<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: Types::INTEGER)]
  private ?int $id = null;

  #[ORM\Column(type: Types::TEXT)]
  #[Assert\NotBlank]
  #[Assert\Length(min: 2)]
  private ?string $content = null;

  #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
  private ?\DateTimeImmutable $createdAt = null;

  #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
  private ?\DateTimeImmutable $updatedAt = null;

  #[ORM\ManyToOne(inversedBy: 'comments')]
  #[ORM\JoinColumn(nullable: false)]
  private ?Trick $trick = null;

  #[ORM\ManyToOne(inversedBy: 'comments')]
  #[ORM\JoinColumn(nullable: false)]
  private ?User $user = null;

  public function __construct()
  {
    $this->createdAt = new \DateTimeImmutable();
    $this->updatedAt = $this->createdAt;
  }
  public function getId(): ?int
  {
    return $this->id;
  }

  public function getContent(): ?string
  {
    return $this->content;
  }

  public function setContent(string $content): static
  {
    $this->content = $content;

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

  public function getTrick(): ?Trick
  {
    return $this->trick;
  }

  public function setTrick(?Trick $trick): static
  {
    $this->trick = $trick;

    return $this;
  }

  public function getUser(): ?User
  {
    return $this->user;
  }

  public function setUser(?User $user): static
  {
    $this->user = $user;

    return $this;
  }
}
