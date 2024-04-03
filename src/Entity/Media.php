<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name: "discriminator", type: Types::STRING)]
#[ORM\DiscriminatorMap(["image" => Image::class, "video" => Video::class, "profile_picture" => ProfilPicture::class])]

abstract class Media
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: Types::INTEGER)]
  protected int $id;

  #[ORM\Column(type: Types::STRING)]
  protected string $name;

  #[ORM\Column(type: Types::STRING)]
  protected string $description;

  #[ORM\Column(type: Types::STRING)]
  protected string $url;

  #[ORM\Column(type: Types::STRING)]
  protected string $externalId;

  #[ORM\Column(type: Types::STRING)]
  protected string $source;

  #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
  protected \DateTimeImmutable $createdAt;

  #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
  protected \DateTimeImmutable $updatedAt;

  public function __construct()
  {
    $this->createdAt = new \DateTimeImmutable();
    $this->updatedAt = $this->createdAt;
  }
  public function getId(): ?int
  {
    return $this->id;
  }

  public function getName(): ?string
  {
    return $this->name;
  }

  public function setName(string $name): static
  {
    $this->name = $name;

    return $this;
  }

  public function getDescription(): ?string
  {
    return $this->description;
  }

  public function setDescription(string $description): static
  {
    $this->description = $description;

    return $this;
  }

  public function getUrl(): ?string
  {
    return $this->url;
  }

  public function setUrl(string $url): static
  {
    $this->url = $url;

    return $this;
  }

  public function getExternalId(): ?string
  {
    return $this->externalId;
  }

  public function setExternalId(string $externalId): static
  {
    $this->externalId = $externalId;

    return $this;
  }

  public function getSource(): ?string
  {
    return $this->source;
  }

  public function setSource(string $source): static
  {
    $this->source = $source;

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
}
