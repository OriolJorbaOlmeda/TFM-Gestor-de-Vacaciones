<?php

namespace App\Entity;

use App\Repository\CalendarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CalendarRepository::class)]
class Calendar
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'date')]
    private $initial_date;

    #[ORM\Column(type: 'date')]
    private $final_date;

    #[ORM\ManyToOne(targetEntity: Company::class, inversedBy: 'calendars')]
    #[ORM\JoinColumn(nullable: false)]
    private $company;

    #[ORM\OneToMany(mappedBy: 'calendar', targetEntity: Petition::class)]
    private $petitions;

    public function __construct()
    {
        $this->petitions = new ArrayCollection();
    }

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

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @return Collection<int, Petition>
     */
    public function getPetitions(): Collection
    {
        return $this->petitions;
    }

    public function addPetition(Petition $petition): self
    {
        if (!$this->petitions->contains($petition)) {
            $this->petitions[] = $petition;
            $petition->setCalendar($this);
        }

        return $this;
    }

    public function removePetition(Petition $petition): self
    {
        if ($this->petitions->removeElement($petition)) {
            // set the owning side to null (unless already changed)
            if ($petition->getCalendar() === $this) {
                $petition->setCalendar(null);
            }
        }

        return $this;
    }
}
