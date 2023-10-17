<?php

namespace App\Entity;

use App\Repository\GalleryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GalleryRepository::class)]
class Gallery
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?bool $published = null;

    #[ORM\ManyToOne(inversedBy: 'galleries')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Member $Member = null;

    #[ORM\ManyToMany(targetEntity: Tape::class, inversedBy: 'galleries')]
    private Collection $tapes;

    public function __construct()
    {
        $this->tapes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function isPublished(): ?bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): static
    {
        $this->published = $published;

        return $this;
    }

    public function getMember(): ?Member
    {
        return $this->Member;
    }

    public function setMember(?Member $Member): static
    {
        $this->Member = $Member;

        return $this;
    }

    /**
     * @return Collection<int, Tape>
     */
    public function getTapes(): Collection
    {
        return $this->tapes;
    }

    public function addTape(Tape $tape): static
    {
        if (!$this->tapes->contains($tape)) {
            $this->tapes->add($tape);
        }

        return $this;
    }

    public function removeTape(Tape $tape): static
    {
        $this->tapes->removeElement($tape);

        return $this;
    }
}
