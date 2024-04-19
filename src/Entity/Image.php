<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ImageRepository;
use App\Entity\Trick;

#[ORM\Entity(repositoryClass: ImageRepository::class)]


final class Image extends Media
{
  #[ORM\ManyToOne(inversedBy: 'images')]
  private ?Trick $trick = null;

  public function __construct()
  {
    parent::__construct();
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
}
