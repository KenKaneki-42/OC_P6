<?php

namespace App\Entity;

use App\Repository\TrickCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrickCategoryRepository::class)]
class TrickCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\OneToMany(targetEntity: Trick::class, mappedBy: 'trickCategory', orphanRemoval: true)]
    private Collection $tricks;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Please enter a category name.')]
    private ?string $name = null;

    public function __construct()
    {
        $this->tricks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Trick>
     */
    public function getTricks(): Collection
    {
        return $this->tricks;
    }

    public function addTricks(Trick $tricks): static
    {
        if (!$this->tricks->contains($tricks)) {
            $this->tricks->add($tricks);
            $tricks->setTrickCategory($this);
        }

        return $this;
    }

    public function removeTricks(Trick $tricks): static
    {
        if ($this->tricks->removeElement($tricks)) {
            // set the owning side to null (unless already changed)
            if ($tricks->getTrickCategory() === $this) {
                $tricks->setTrickCategory(null);
            }
        }

        return $this;
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
}
