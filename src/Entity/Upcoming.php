<?php

namespace App\Entity;

use App\Repository\UpcomingRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UpcomingRepository::class)]
class Upcoming
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['movies'])]
    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[Groups(['movies'])]
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[Groups(['movies'])]
    #[ORM\Column]
    private array $content = [];

    #[ORM\Column]
    private ?int $period = null;

    #[ORM\Column]
    private ?int $nbItems = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getContent(): array
    {
        return $this->content;
    }

    public function setContent(array $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPeriod(): ?int
    {
        return $this->period;
    }

    public function setPeriod(int $period): self
    {
        $this->period = $period;

        return $this;
    }

    public function getNbItems(): ?int
    {
        return $this->nbItems;
    }

    public function setNbItems(int $nbItems): self
    {
        $this->nbItems = $nbItems;

        return $this;
    }
}
