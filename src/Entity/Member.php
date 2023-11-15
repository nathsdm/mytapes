<?php

namespace App\Entity;

use App\Repository\MemberRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MemberRepository::class)]
class Member
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'member', targetEntity: Inventory::class, cascade: ['persist', 'remove'])]
    private Collection $inventory;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 5000, nullable: true)]
    private ?string $bio = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $birth = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $creation = null;

    #[ORM\OneToMany(mappedBy: 'Member', targetEntity: Gallery::class, cascade: ['persist', 'remove'])]
    private Collection $galleries;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $User = null;

    public function __construct()
    {
        $this->inventory = new ArrayCollection();
        $this->galleries = new ArrayCollection();
        $this->tapesLiked = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Inventory>
     */
    public function getInventory(): Collection
    {
        return $this->inventory;
    }

    public function addInventory(Inventory $inventory): static
    {
        if (!$this->inventory->contains($inventory)) {
            $this->inventory->add($inventory);
            $inventory->setMember($this);
        }

        return $this;
    }

    public function removeInventory(Inventory $inventory): static
    {
        if ($this->inventory->removeElement($inventory)) {
            // set the owning side to null (unless already changed)
            if ($inventory->getMember() === $this) {
                $inventory->setMember(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getName() ?? '';
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

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): static
    {
        $this->bio = $bio;

        return $this;
    }

    public function getBirth(): ?\DateTimeInterface
    {
        return $this->birth;
    }

    public function setBirth(?\DateTimeInterface $birth): static
    {
        $this->birth = $birth;

        return $this;
    }

    public function getCreation(): ?\DateTimeInterface
    {
        return $this->creation;
    }

    public function setCreation(?\DateTimeInterface $creation): static
    {
        $this->creation = $creation;

        return $this;
    }

    /**
     * @return Collection<int, Gallery>
     */
    public function getGalleries(): Collection
    {
        return $this->galleries;
    }

    public function getGallery(int $id): ?Gallery
    {
        return $this->galleries[$id] ?? null;
    }

    public function addGallery(Gallery $gallery): static
    {
        if (!$this->galleries->contains($gallery)) {
            $this->galleries->add($gallery);
            $gallery->setMember($this);
        }

        return $this;
    }

    public function hasGallery(): bool
    {
        return !$this->galleries->isEmpty();
    }

    public function removeGallery(Gallery $gallery): static
    {
        if ($this->galleries->removeElement($gallery)) {
            // set the owning side to null (unless already changed)
            if ($gallery->getMember() === $this) {
                $gallery->setMember(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(User $User): static
    {
        $this->User = $User;

        return $this;
    }

    public function getTapes(): Collection
    {
        $tapes = new ArrayCollection();
        foreach ($this->getInventory() as $inventory) {
            foreach ($inventory->getTapes() as $tape) {
                $tapes->add($tape);
            }
        }

        return $tapes;
    }

}
