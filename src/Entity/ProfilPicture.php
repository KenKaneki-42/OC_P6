<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProfilPictureRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProfilPictureRepository::class)]
class ProfilPicture extends Media
{
    #[ORM\ManyToOne(inversedBy: 'profilPictures')]
    private ?User $user = null;

    // #[ORM\Column(type: Types::STRING)]
    // private string $alt;

    // public function getAlt(): ?string
    // {
    //     return $this->alt;
    // }

    // public function setAlt(string $alt): static
    // {
    //     $this->alt = $alt;

    //     return $this;
    // }

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
