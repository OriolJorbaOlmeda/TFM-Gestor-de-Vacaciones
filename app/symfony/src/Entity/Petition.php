<?php

namespace App\Entity;

use App\Repository\PetitionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PetitionRepository::class)]
class Petition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'date')]
    private $initial_date;

    #[ORM\Column(type: 'date')]
    private $final_date;

    #[ORM\Column(type: 'integer')]
    private $duration;

    #[ORM\Column(type: 'string', length: 255)]
    private $state;

    #[ORM\Column(type: 'string', length: 255)]
    private $type;

    #[ORM\Column(type: 'string', length: 255)]
    private $reason;

    #[ORM\Column(type: 'date')]
    private $petition_date;

    #[ORM\OneToOne(inversedBy: 'petition', targetEntity: User::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private $employee;

    #[ORM\ManyToOne(targetEntity: Calendar::class, inversedBy: 'petitions')]
    private $calendar;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInitialDate(): ?\DateTimeInterface
    {
        return $this->initial_date;
    }

    public function setInitialDate(\DateTimeInterface $initial_date): self
    {
        $this->initial_date = $initial_date;

        return $this;
    }

    public function getFinalDate(): ?\DateTimeInterface
    {
        return $this->final_date;
    }

    public function setFinalDate(\DateTimeInterface $final_date): self
    {
        $this->final_date = $final_date;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
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

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(string $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    public function getPetitionDate(): ?\DateTimeInterface
    {
        return $this->petition_date;
    }

    public function setPetitionDate(\DateTimeInterface $petition_date): self
    {
        $this->petition_date = $petition_date;

        return $this;
    }

    public function getEmployee(): ?User
    {
        return $this->employee;
    }

    public function setEmployee(User $employee): self
    {
        $this->employee = $employee;

        return $this;
    }

    public function getCalendar(): ?Calendar
    {
        return $this->calendar;
    }

    public function setCalendar(?Calendar $calendar): self
    {
        $this->calendar = $calendar;

        return $this;
    }
}
