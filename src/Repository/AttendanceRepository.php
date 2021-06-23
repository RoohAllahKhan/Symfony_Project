<?php

namespace App\Repository;

use App\Entity\Attendance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method Attendance|null find($id, $lockMode = null, $lockVersion = null)
 * @method Attendance|null findOneBy(array $criteria, array $orderBy = null)
 * @method Attendance[]    findAll()
 * @method Attendance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AttendanceRepository extends ServiceEntityRepository
{
    private $entityManager;


    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Attendance::class);
        $this->entityManager = $entityManager;
    }

    // /**
    //  * @return Attendance[] Returns an array of Attendance objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Attendance
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function monthyReport($month, $year)
    {
        $query = $this->entityManager->createQueryBuilder();
        $query->select('a.date, sum(case when a.status= :p_status then 1 else 0 end) as Present, 
                        sum(case when a.status= :l_status then 1 else 0 end) as Leave,
                        sum(case when a.status = :a_status then 1 else 0 end) as Absent')
            ->from('App:Attendance', 'a')
            ->where($query->expr()->like('a.date', ':pattern' ) )
            ->groupBy('a.date')
            ->orderBy('a.date')
            ->setParameter('p_status', 'P')
            ->setParameter('l_status', 'L')
            ->setParameter('a_status', '^')
            ->setParameter('pattern', '%/'.$month.'/'.$year);
        return $query->getQuery()->getResult();
    }
    public function todaysAttendace($date)
    {
        $query = $this->entityManager->createQueryBuilder();
        $query->select('attendance')
            ->from('App:Attendance', 'attendance')
            ->where('attendance.date = :todayDate')
            ->setParameter('todayDate', $date);

        return $query->getQuery()->getResult();
    }

    public function checkAttendance($employee, $date)
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('attendance')
            ->from('App:Attendance', 'attendance')
            ->where('attendance.employee= :empId')
            ->andWhere('attendance.date = :date')
            ->setParameters(array('empId'=>$employee, 'date'=>$date));

        return $qb->getQuery()->getResult();

    }
    public function updateAttendance($employee, $date, $time)
    {

        $records = $this->checkAttendance($employee, $date);
        if($records['0']->getTimeIn() == null)
        {
            $records['0']->setTimeIn($time);
            $this->entityManager->flush();
        }
        elseif($records['0']->getTimeOut() == null)
        {
            $records['0']->setTimeOut($time);
            $this->entityManager->flush();
        }

    }
    public function markAttendance($date, $employee, $timeIn, $timeOut, $status)
    {
        $currAttendance = new Attendance();
        $currAttendance->setDate($date);
        $currAttendance->setEmployeeId($employee);
        $currAttendance->setTimeIn($timeIn);
        $currAttendance->setTimeOut($timeOut);
        $currAttendance->setStatus($status);

        $this->entityManager->persist($currAttendance);
        $this->entityManager->flush();

    }
}
