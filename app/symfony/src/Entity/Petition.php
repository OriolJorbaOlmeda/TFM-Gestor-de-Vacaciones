<?php

namespace App\Entity;

use App\Modules\Petition\Infrastucture\PetitionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PetitionRepository::class)]
class Petition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[Assert\GreaterThan('today', message: 'La fecha de inicio debe ser mayor a la fecha actual')]
    #[Assert\LessThanOrEqual(propertyPath: 'final_date', message: 'La fecha inicio debe ser inferior o igual a la fecha de fin')]
    #[ORM\Column(type: 'date')]
    private $initial_date;

    #[Assert\GreaterThan('today', message: 'La fecha fin debe ser mayor a la fecha actual')]
    #[Assert\GreaterThanOrEqual(propertyPath: 'initial_date', message: 'La fecha fin debe ser superior o igual a la fecha de inicio')]
    #[ORM\Column(type: 'date')]
    private $final_date;

    #[ORM\Column(type: 'integer')]
    private $duration;

    #[ORM\Column(type: 'string', length: 255)]
    private $state;

    #[ORM\Column(type: 'string', length: 255)]
    private $type;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $reason;

    #[ORM\Column(type: 'date')]
    private $petition_date;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'petitions')]
    private $employee;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'supervisor_petitions')]
    private $supervisor;

    #[ORM\ManyToOne(targetEntity: Calendar::class, inversedBy: 'petitions')]
    private $calendar;

    #[ORM\OneToOne(mappedBy: 'petition', targetEntity: Justify::class, cascade: ['persist', 'remove'])]
    private $justify;

    private $justify_content;

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

    public function getSupervisor(): ?User
    {
        return $this->supervisor;
    }

    public function setSupervisor(User $supervisor): self
    {
        $this->supervisor = $supervisor;

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

    public function getJustify(): ?Justify
    {
        return $this->justify;
    }

    public function setJustify(?Justify $justify): self
    {
        // unset the owning side of the relation if necessary
        if ($justify === null && $this->justify !== null) {
            $this->justify->setPetition(null);
        }

        // set the owning side of the relation if necessary
        if ($justify !== null && $justify->getPetition() !== $this) {
            $justify->setPetition($this);
        }

        $this->justify = $justify;

        return $this;
    }

    public function getJustifyContent()
    {
        return $this->justify_content;
    }

    public function setJustifyContent($justify_content): void
    {
        $this->justify_content = $justify_content;
    }
}
