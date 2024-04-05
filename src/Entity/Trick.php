<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TrickRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrickRepository::class)]
class Trick
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column(length: 255, unique: true)]
  private ?string $name = null;

  #[ORM\Column(length: 255)]
  private ?string $description = null;

  #[ORM\Column]
  private ?\DateTimeImmutable $createdAt = null;

  #[ORM\Column]
  private ?\DateTimeImmutable $updatedAt = null;

  #[ORM\Column(length: 255, unique: true)]
  private ?string $slug = null;

  #[ORM\ManyToOne(inversedBy: 'tricks')]
  #[ORM\JoinColumn(nullable: false)]
  private ?User $user = null;

  #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'trick', orphanRemoval: true)]
  private Collection $comments;

  #[ORM\OneToMany(targetEntity: Image::class, mappedBy: 'trick')]
  private Collection $images;

  #[ORM\OneToMany(targetEntity: Video::class, mappedBy: 'trick')]
  private Collection $videos;

  #[ORM\ManyToOne(inversedBy: 'name')]
  #[ORM\JoinColumn(nullable: false)]
  private ?TrickCategory $trickCategory = null;

  public function __construct()
  {
    $this->comments = new ArrayCollection();
    $this->images = new ArrayCollection();
    $this->videos = new ArrayCollection();
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

  public function getSlug(): ?string
  {
    return $this->slug;
  }

  public function setSlug(string $slug): static
  {
    $this->slug = $slug;

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
      $comment->setTrick($this);
    }

    return $this;
  }

  public function removeComment(Comment $comment): static
  {
    if ($this->comments->removeElement($comment)) {
      // set the owning side to null (unless already changed)
      if ($comment->getTrick() === $this) {
        $comment->setTrick(null);
      }
    }

    return $this;
  }

  /**
   * @return Collection<int, Image>
   */
  public function getImages(): Collection
  {
    return $this->images;
  }

  public function addImage(Image $image): static
  {
    if (!$this->images->contains($image)) {
      $this->images->add($image);
      $image->setTrick($this);
    }

    return $this;
  }

  public function removeImage(Image $image): static
  {
    if ($this->images->removeElement($image)) {
      // set the owning side to null (unless already changed)
      if ($image->getTrick() === $this) {
        $image->setTrick(null);
      }
    }

    return $this;
  }

  /**
   * @return Collection<int, Video>
   */
  public function getVideos(): Collection
  {
    return $this->videos;
  }

  public function addVideo(Video $video): static
  {
    if (!$this->videos->contains($video)) {
      $this->videos->add($video);
      $video->setTrick($this);
    }

    return $this;
  }

  public function removeVideo(Video $video): static
  {
    if ($this->videos->removeElement($video)) {
      // set the owning side to null (unless already changed)
      if ($video->getTrick() === $this) {
        $video->setTrick(null);
      }
    }

    return $this;
  }

  public function getTrickCategory(): ?TrickCategory
  {
    return $this->trickCategory;
  }

  public function setTrickCategory(?TrickCategory $trickCategory): static
  {
    $this->trickCategory = $trickCategory;

    return $this;
  }
}
