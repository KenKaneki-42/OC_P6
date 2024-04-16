<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name: "discriminator", type: Types::STRING)]
#[ORM\DiscriminatorMap(["image" => Image::class, "video" => Video::class, "profile_picture" => ProfilPicture::class])]

abstract class Media
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: Types::INTEGER)]
  protected ?int $id = null;

  #[ORM\Column(type: Types::STRING)]
  #[Assert\NotBlank]
  protected ?string $name = null;

  #[ORM\Column(type: Types::STRING)]
  protected ?string $description = null;

  #[ORM\Column(type: Types::STRING)]
  protected ?string $url = null;

  #[ORM\Column(type: Types::STRING)]
  protected ?string $externalId = null;

  #[ORM\Column(type: Types::STRING)]
  protected ?string $source = null;

  #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
  protected \DateTimeImmutable $createdAt;

  #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
  protected \DateTimeImmutable $updatedAt;

  protected ?File $file = null;

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

  public function getFile(): ?File
  {
      return $this->file;
  }

  public function setFile(?File $file): static
  {
      $this->file = $file;

      return $this;
  }
}
