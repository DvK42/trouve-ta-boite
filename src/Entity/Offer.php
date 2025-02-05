<?php

namespace App\Entity;

use App\Repository\OfferRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OfferRepository::class)]
class Offer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(nullable: true)]
    private ?float $salary = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private array $missionList = [];

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private array $profileSearchedList = [];

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $maxApplyDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $deletedAt = null;


    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        if (!$this->createdAt) {
            $this->createdAt = new \DateTime();
        }
    }
    
    #[ORM\ManyToOne(inversedBy: 'offers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Company $company = null;

    /**
     * @var Collection<int, Application>
     */
    #[ORM\OneToMany(targetEntity: Application::class, mappedBy: 'offer')]
    private Collection $applications;

    /**
     * @var Collection<int, Skill>
     */
    #[ORM\ManyToMany(targetEntity: Skill::class, mappedBy: 'offer')]
    private Collection $skills;

    /**
     * @var Collection<int, Category>
     */
    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'offers')]
    #[ORM\JoinTable(name: 'offer_category')]
    private Collection $categories;

    public function __construct()
    {
        $this->applications = new ArrayCollection();
        $this->skills = new ArrayCollection();
        $this->categories = new ArrayCollection();
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

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

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getSalary(): ?float
    {
        return $this->salary;
    }

    public function setSalary(?float $salary): static
    {
        $this->salary = $salary;

        return $this;
    }

    /**
     * @return array
     */
    public function getMissionList(): array
    {
        return $this->missionList;
    }

    /**
     * @param array $missionList
     */
    public function setMissionList(array $missionList): self
    {
        $this->missionList = $missionList;

        return $this;
    }

    public function addMission(string $mission): self
    {
        if (!in_array($mission, $this->missionList, true)) {
            $this->missionList[] = $mission;
        }

        return $this;
    }

    public function removeMission(string $mission): self
    {
        $index = array_search($mission, $this->missionList, true);
        if ($index !== false) {
            unset($this->missionList[$index]);
            $this->missionList = array_values($this->missionList); // Réindexer l'array
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getProfileSearchedList(): array
    {
        return $this->profileSearchedList;
    }

    /**
     * @param array $missions
     */
    public function setProfileSearchedList(array $profileSearchedList): self
    {
        $this->profileSearchedList = $profileSearchedList;

        return $this;
    }

    public function addProfileSearched(string $profileSearched): self
    {
        if (!in_array($profileSearched, $this->profileSearchedList, true)) {
            $this->profileSearchedList[] = $profileSearched;
        }

        return $this;
    }

    public function removeProfileSearched(string $profileSearched): self
    {
        $index = array_search($profileSearched, $this->profileSearchedList, true);
        if ($index !== false) {
            unset($this->profileSearchedList[$index]);
            $this->profileSearchedList = array_values($this->profileSearchedList); // Réindexer l'array
        }

        return $this;
    }

    public function getMaxApplyDate(): ?\DateTimeInterface
    {
        return $this->maxApplyDate;
    }

    public function setMaxApplyDate(?\DateTimeInterface $maxApplyDate): self
    {
        $this->maxApplyDate = $maxApplyDate;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): static
    {
        $this->company = $company;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->company ? $this->company->getLocation() : null;
    }
    
    /**
     * @return Collection<int, Application>
     */
    public function getApplications(): Collection
    {
        return $this->applications;
    }

    public function getApplicationsCount(): int
{
    return $this->applications->count();
}

    public function addApplication(Application $application): static
    {
        if (!$this->applications->contains($application)) {
            $this->applications->add($application);
            $application->setOffer($this);
        }

        return $this;
    }

    public function removeApplication(Application $application): static
    {
        if ($this->applications->removeElement($application)) {
            // set the owning side to null (unless already changed)
            if ($application->getOffer() === $this) {
                $application->setOffer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Skill>
     */
    public function getSkills(): Collection
    {
        return $this->skills;
    }

    public function addSkill(Skill $skill): static
    {
        if (!$this->skills->contains($skill)) {
            $this->skills->add($skill);
            $skill->addOffer($this);
        }

        return $this;
    }

    public function removeSkill(Skill $skill): static
    {
        if ($this->skills->removeElement($skill)) {
            $skill->removeOffer($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->addOffer($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        if ($this->categories->removeElement($category)) {
            if ($category->getOffers() === $this) {
                $category->addOffer($this);
            }
        }

        return $this;
    }
    
    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }
    
    public function setDeletedAt(?\DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;
        return $this;
    }
    
    public function softDelete(): self
    {
        $this->deletedAt = new \DateTimeImmutable();
        return $this;
    }
}