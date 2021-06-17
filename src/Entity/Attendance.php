<?php

namespace App\Entity;

use App\Repository\AttendanceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AttendanceRepository::class)
 */
class Attendance
{


    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity=Employees::class, inversedBy="attendances")
     * @ORM\JoinColumn(nullable=false)
     */
    private $employee;

    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=255)
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="/\b([0-9]|0[0-9]|1[0-9]|2[0-3])\b:\b([0-9]|0[0-9]|1[0-9]|2[0-9]|3[0-9]|4[0-9]|5[0-9])\b$/"
     * )
     */
    private $time_in;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Regex(
     *     pattern="/\b([0-9]|0[0-9]|1[0-9]|2[0-3])\b:\b([0-9]|0[0-9]|1[0-9]|2[0-9]|3[0-9]|4[0-9]|5[0-9])\b$/"
     * )
     */
    private $time_out;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;


    public function getEmployeeId(): ?Employees
    {
        return $this->employee;
    }

    public function setEmployeeId(?Employees $employee): self
    {
        $this->employee = $employee;

        return $this;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getTimeIn(): ?string
    {
        return $this->time_in;
    }

    public function setTimeIn(string $time_in): self
    {
        $this->time_in = $time_in;

        return $this;
    }

    public function getTimeOut(): ?string
    {
        return $this->time_out;
    }

    public function setTimeOut(?string $time_out): self
    {
        $this->time_out = $time_out;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
