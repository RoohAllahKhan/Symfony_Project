<?php

namespace App\Entity;

use App\Repository\EmployeesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EmployeesRepository::class)
 */
class Employees
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $empName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $department;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotNull()
     * @Assert\Regex(
     *     pattern="/^[0-9]*$/"
     * )
     */
    private $salary;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $profile_pic;

    /**
     * @ORM\ManyToOne(targetEntity=Designations::class, inversedBy="employees")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     */
    private $designation;

    /**
     * @ORM\ManyToOne(targetEntity=Employees::class, inversedBy="employee_boss")
     */
    private $boss;

    /**
     * @ORM\OneToMany(targetEntity=Employees::class, mappedBy="boss")
     */
    private $employee_boss;

    /**
     * @ORM\OneToMany(targetEntity=Attendance::class, mappedBy="employee")
     */
    private $attendances;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="employee", cascade={"persist", "remove"})
     */
    private $login;

    public function __construct()
    {
        $this->employee_boss = new ArrayCollection();
        $this->attendances = new ArrayCollection();
    }

//    public function setId(int $id): ?self
//    {
//        $this->id = $id;
//        return $this;
//    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmpName(): ?string
    {
        return $this->empName;
    }

    public function setEmpName(string $empName): self
    {
        $this->empName = $empName;

        return $this;
    }
    public function __toString() {
        $test = "Rooh";
        return $test;
    }

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function setDepartment(string $department): self
    {
        $this->department = $department;

        return $this;
    }

    public function getSalary(): ?int
    {
        return $this->salary;
    }

    public function setSalary(int $salary): self
    {
        $this->salary = $salary;

        return $this;
    }

    public function getProfilePic(): ?string
    {
        return $this->profile_pic;
    }

    public function setProfilePic(string $profile_pic): self
    {
        $this->profile_pic = $profile_pic;

        return $this;
    }

    public function getDesignation(): ?Designations
    {
        return $this->designation;
    }

    public function setDesignation(?Designations $designation): self
    {
        $this->designation = $designation;

        return $this;
    }

    public function getBoss(): ?self
    {
        return $this->boss;
    }

    public function setBoss(?self $boss): self
    {
        $this->boss = $boss;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getEmployeeBoss(): Collection
    {
        return $this->employee_boss;
    }

    public function addEmployeeBoss(self $employeeBoss): self
    {
        if (!$this->employee_boss->contains($employeeBoss)) {
            $this->employee_boss[] = $employeeBoss;
            $employeeBoss->setBoss($this);
        }

        return $this;
    }

    public function removeEmployeeBoss(self $employeeBoss): self
    {
        if ($this->employee_boss->removeElement($employeeBoss)) {
            // set the owning side to null (unless already changed)
            if ($employeeBoss->getBoss() === $this) {
                $employeeBoss->setBoss(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Attendance[]
     */
    public function getAttendances(): Collection
    {
        return $this->attendances;
    }

    public function addAttendance(Attendance $attendance): self
    {
        if (!$this->attendances->contains($attendance)) {
            $this->attendances[] = $attendance;
            $attendance->setEmployeeId($this);
        }

        return $this;
    }

    public function removeAttendance(Attendance $attendance): self
    {
        if ($this->attendances->removeElement($attendance)) {
            // set the owning side to null (unless already changed)
            if ($attendance->getEmployeeId() === $this) {
                $attendance->setEmployeeId(null);
            }
        }

        return $this;
    }

    public function getLogin(): ?User
    {
        return $this->login;
    }

    public function setLogin(User $login): self
    {
        // set the owning side of the relation if necessary
        if ($login->getEmployee() !== $this) {
            $login->setEmployee($this);
        }

        $this->login = $login;

        return $this;
    }
}
