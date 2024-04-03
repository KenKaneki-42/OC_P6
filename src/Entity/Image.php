<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
// use Doctrine\DBAL\Types\Types;
use App\Repository\ImageRepository;

#[ORM\Entity(repositoryClass: ImageRepository::class)]


class Image extends Media
{
  #[ORM\ManyToOne(inversedBy: 'images')]
  private ?Trick $trick = null;

  // #[ORM\ManyToOne(targetEntity: Trick::class, inversedBy: 'image')]
  // #[ORM\JoinColumn(nullable: false)]
  // private Trick $trick;

  // public function getTrick(): Trick
  // {
  //   return $this->trick;
  // }

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
