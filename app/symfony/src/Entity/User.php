<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180)]
    private $name;

    #[ORM\Column(type: 'string', length: 180)]
    private $lastname;

    #[ORM\Column(type: 'string', length: 180)]
    private $direction;

    #[ORM\Column(type: 'string', length: 180)]
    private $city;

    #[ORM\Column(type: 'string', length: 180)]
    private $province;

    #[ORM\Column(type: 'string', length: 180)]
    private $postalcode;

    #[ORM\Column(type: 'integer')]
    private $total_vacation_days;

    #[ORM\Column(type: 'integer')]
    private $pending_vacation_days;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    //#[Assert\Regex('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/')]
    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\ManyToOne(targetEntity: Department::class, inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private $department;

    #[ORM\OneToMany(mappedBy: 'employee', targetEntity: Petition::class, cascade: ['persist', 'remove'])]
    private $petitions;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'users')]
    private $supervisor;

    #[ORM\OneToMany(mappedBy: 'supervisor', targetEntity: self::class)]
    private $employees;

    public function __construct()
    {
        $this->employees = new ArrayCollection();
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getLastname()
    {
        return $this->lastname;
    }

    public function setLastname($lastname): void
    {
        $this->lastname = $lastname;
    }

    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * @param mixed $direction
     */
    public function setDirection($direction): void
    {
        $this->direction = $direction;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city): void
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * @param mixed $province
     */
    public function setProvince($province): void
    {
        $this->province = $province;
    }

    /**
     * @return mixed
     */
    public function getPostalcode()
    {
        return $this->postalcode;
    }

    /**
     * @param mixed $postalcode
     */
    public function setPostalcode($postalcode): void
    {
        $this->postalcode = $postalcode;
    }

    /**
     * @return mixed
     */
    public function getTotalVacationDays()
    {
        return $this->total_vacation_days;
    }

    /**
     * @param mixed $total_vacation_days
     */
    public function setTotalVacationDays($total_vacation_days): void
    {
        $this->total_vacation_days = $total_vacation_days;
    }

    /**
     * @return mixed
     */
    public function getPendingVacationDays()
    {
        return $this->pending_vacation_days;
    }

    /**
     * @param mixed $pending_vacation_days
     */
    public function setPendingVacationDays($pending_vacation_days): void
    {
        $this->pending_vacation_days = $pending_vacation_days;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    public function setDepartment(?Department $department): self
    {
        $this->department = $department;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getPetitions(): Collection
    {
        return $this->petitions;
    }

    public function addPetition(Petition $petition): self
    {
        if (!$this->petitions->contains($petition)) {
            $this->petitions[] = $petition;
            $petition->setEmployee($this);
        }

        return $this;
    }

    public function removePetition(Petition $petition): self
    {
        if ($this->petitions->removeElement($petition)) {
            // set the owning side to null (unless already changed)
            if ($petition->getEmployee() === $this) {
                $petition->setEmployee(null);
            }
        }

        return $this;
    }

    public function getSupervisor(): ?self
    {
        return $this->supervisor;
    }

    public function setSupervisor(?self $supervisor): self
    {
        $this->supervisor = $supervisor;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getEmployees(): Collection
    {
        return $this->employees;
    }

    public function addEmployee(self $user): self
    {
        if (!$this->employees->contains($user)) {
            $this->employees[] = $user;
            $user->setSupervisor($this);
        }

        return $this;
    }

    public function removeEmployee(self $user): self
    {
        if ($this->employees->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getSupervisor() === $this) {
                $user->setSupervisor(null);
            }
        }

        return $this;
    }
}
