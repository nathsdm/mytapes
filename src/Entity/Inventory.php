<?php

namespace App\Entity;

use App\Repository\InventoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InventoryRepository::class)]
class Inventory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'inventory', targetEntity: Tape::class)]
    private $tapes;

    public function __construct()
    {
        $this->tapes = new ArrayCollection();
    }

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'inventory')]
    private ?Member $member = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Tape[]
     */
    public function getTapes(): Collection
    {
        return $this->tapes;
    }

    public function addTape(Tape $tape): self
    {
        if (!$this->tapes->contains($tape)) {
            $this->tapes[] = $tape;
        }

        return $this;
    }

    public function removeTape(Tape $tape): self
    {
        $this->tapes->removeElement($tape);

        return $this;
    }

    public function __toString(): string
    {
        return 'Inventory #' . $this->getId();
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

    public function getMember(): ?Member
    {
        return $this->member;
    }

    public function setMember(?Member $member): static
    {
        $this->member = $member;

        return $this;
    }
}
