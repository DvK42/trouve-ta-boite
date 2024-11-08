<?php

namespace App\Entity;

use App\Repository\ApplicationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ApplicationRepository::class)]
class Application
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $coverLetter = null;

    #[ORM\ManyToOne(inversedBy: 'applications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Student $studentId = null;

    #[ORM\ManyToOne(inversedBy: 'applications')]
    private ?Offer $offerId = null;

    /**
     * @var Collection<int, Message>
     */
    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'applicationId')]
    private Collection $messages;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getCoverLetter(): ?string
    {
        return $this->coverLetter;
    }

    public function setCoverLetter(string $coverLetter): static
    {
        $this->coverLetter = $coverLetter;

        return $this;
    }

    public function getStudentId(): ?Student
    {
        return $this->studentId;
    }

    public function setStudentId(?Student $studentId): static
    {
        $this->studentId = $studentId;

        return $this;
    }

    public function getOfferId(): ?Offer
    {
        return $this->offerId;
    }

    public function setOfferId(?Offer $offerId): static
    {
        $this->offerId = $offerId;

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setApplicationId($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getApplicationId() === $this) {
                $message->setApplicationId(null);
            }
        }

        return $this;
    }
}
