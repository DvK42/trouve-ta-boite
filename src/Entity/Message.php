<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $senderId = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    private ?User $recipientId = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    private ?Application $applicationId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
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

    public function getSenderId(): ?User
    {
        return $this->senderId;
    }

    public function setSenderId(?User $senderId): static
    {
        $this->senderId = $senderId;

        return $this;
    }

    public function getRecipientId(): ?User
    {
        return $this->recipientId;
    }

    public function setRecipientId(?User $recipientId): static
    {
        $this->recipientId = $recipientId;

        return $this;
    }

    public function getApplicationId(): ?Application
    {
        return $this->applicationId;
    }

    public function setApplicationId(?Application $applicationId): static
    {
        $this->applicationId = $applicationId;

        return $this;
    }
}
