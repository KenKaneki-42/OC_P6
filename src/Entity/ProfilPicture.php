<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProfilPictureRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProfilPictureRepository::class)]
final class ProfilPicture extends Media
{
  #[ORM\ManyToOne(inversedBy: 'profilPictures')]
  private ?User $user = null;

  public function __construct()
  {
    parent::__construct();
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
