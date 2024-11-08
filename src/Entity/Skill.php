<?php

namespace App\Entity;

use App\Repository\SkillRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SkillRepository::class)]
class Skill
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Offer>
     */
    #[ORM\ManyToMany(targetEntity: Offer::class, inversedBy: 'skills')]
    private Collection $offerId;

    /**
     * @var Collection<int, Student>
     */
    #[ORM\ManyToMany(targetEntity: Student::class, inversedBy: 'skills')]
    private Collection $studentId;

    public function __construct()
    {
        $this->offerId = new ArrayCollection();
        $this->studentId = new ArrayCollection();
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

    /**
     * @return Collection<int, Offer>
     */
    public function getOfferId(): Collection
    {
        return $this->offerId;
    }

    public function addOfferId(Offer $offerId): static
    {
        if (!$this->offerId->contains($offerId)) {
            $this->offerId->add($offerId);
        }

        return $this;
    }

    public function removeOfferId(Offer $offerId): static
    {
        $this->offerId->removeElement($offerId);

        return $this;
    }

    /**
     * @return Collection<int, Student>
     */
    public function getStudentId(): Collection
    {
        return $this->studentId;
    }

    public function addStudentId(Student $studentId): static
    {
        if (!$this->studentId->contains($studentId)) {
            $this->studentId->add($studentId);
        }

        return $this;
    }

    public function removeStudentId(Student $studentId): static
    {
        $this->studentId->removeElement($studentId);

        return $this;
    }
}
