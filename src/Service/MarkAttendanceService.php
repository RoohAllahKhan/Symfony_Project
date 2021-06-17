<?php


namespace App\Service;


use App\Entity\Attendance;
use App\Repository\AttendanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MarkAttendanceService extends AbstractController
{
    private $attendanceRepository;

    private $entityManager;

    public function __construct(AttendanceRepository $attendanceRepository, EntityManagerInterface $entityManager)
    {

        $this->attendanceRepository = $attendanceRepository;
        $this->entityManager = $entityManager;
    }

    public function monthReport($month, $year)
    {
        return $this->entityManager->getRepository(Attendance::class)->monthyReport($month, $year);
    }
    public function todayAttendance($date)
    {
        return $this->entityManager->getRepository(Attendance::class)->todaysAttendace($date);
    }

    public function setAttendanceUpdation($empRecord, $attendanceForm, $employee, $date, $entityManager)
    {

            $time_in = $empRecord['0']->getTimeIn();
            $time_out = $empRecord['0']->getTimeOut();
            $entityManager = $this->getDoctrine()->getManager();
            $empAttendance = $entityManager->getRepository(Attendance::class)->findOneBy(array('employee'=>$employee, 'date'=>$date));

            if($time_in == null){
                $timeIn = $attendanceForm->getViewData()->getTimeIn();
                $entityManager->getRepository(Attendance::class)->updateAttendance($employee, $date, $timeIn);
            }
            elseif($time_out == null)
            {
                $timeOut = $attendanceForm->getViewData()->getTimeOut();
                $entityManager->getRepository(Attendance::class)->updateAttendance($employee, $date, $timeOut);
            }
    }

    public function setAttendance($attendanceForm, $user, $hour)
    {
        $date = date("d/m/Y");
        $employee = $user->getEmployee();
        $timeIn = $attendanceForm->getViewData()->getTimeIn();
        $timeOut = $attendanceForm->getViewData()->getTimeOut();
        if($hour >= 11 && $hour < 12){
            $status = 'L';

        }
        elseif ($hour >= 12)
        {
            $status = '^';
        }
        else{
            $status = 'P';
        }
        $this->attendanceRepository->markAttendance($date, $employee, $timeIn, $timeOut, $status);
    }
}