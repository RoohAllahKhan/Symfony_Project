<?php

namespace App\Entity;

use App\Repository\DesignationsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DesignationsRepository::class)
 */
class Designations
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $designation_name;

    /**
     * @ORM\OneToMany(targetEntity=Employees::class, mappedBy="designation")
     */
    private $employees;

    public function __construct()
    {
        $this->employees = new ArrayCollection();
    }
    
    public function __toString()
    {
        return $this->designation_name;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDesignationName(): ?string
    {
        return $this->designation_name;
    }

    public function setDesignationName(string $designation_name): self
    {
        $this->designation_name = $designation_name;

        return $this;
    }

    /**
     * @return Collection|Employees[]
     */
    public function getEmployees(): Collection
    {
        return $this->employees;
    }

    public function addEmployee(Employees $employee): self
    {
        if (!$this->employees->contains($employee)) {
            $this->employees[] = $employee;
            $employee->setDesignation($this);
        }

        return $this;
    }

    public function removeEmployee(Employees $employee): self
    {
        if ($this->employees->removeElement($employee)) {
            // set the owning side to null (unless already changed)
            if ($employee->getDesignation() === $this) {
                $employee->setDesignation(null);
            }
        }

        return $this;
    }
}
