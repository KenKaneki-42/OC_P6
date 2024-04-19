<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\VideoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VideoRepository::class)]
final class Video extends Media
{
    #[ORM\ManyToOne(inversedBy: 'videos')]
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
