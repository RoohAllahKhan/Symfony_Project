<?php

namespace App\Repository;

use App\Entity\Designations;
use App\Entity\Employees;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @method Employees|null find($id, $lockMode = null, $lockVersion = null)
 * @method Employees|null findOneBy(array $criteria, array $orderBy = null)
 * @method Employees[]    findAll()
 * @method Employees[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeesRepository extends ServiceEntityRepository
{



    private $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Employees::class);
        $this->entityManager = $entityManager;
    }

    public function delEmployee($empId)
    {
        $conn = $this->getEntityManager()->getConnection();
        $delAttendance = 'DELETE FROM attendance a where a.employee_id = :empId';
        $stmt1 = $conn->prepare($delAttendance);
        $stmt1->execute(['empId' => $empId]);

        $delUser = 'DELETE FROM user u where u.employee_id = :empId';
        $stmt2 = $conn->prepare($delUser);
        $stmt2->execute(['empId' => $empId]);

        $delEmp = 'DELETE FROM employees e where e.id = :empId';
        $stmt3 = $conn->prepare($delEmp);
        $stmt3->execute(['empId' => $empId]);

    }

    public function updateEmployee($empId, $empName, $department, $salary, $profile, $designation, $boss, $email, $password, $passwordEncoder)
    {
        $employee = $this->entityManager->createQueryBuilder();
        $employee->select('e')
            ->from('App:Employees', 'e')
            ->where('e.id= :empId')
            ->setParameter('empId', $empId);
        $records = $employee->getQuery()->getResult();
        if($records !== null)
        {
            $records['0']->setEmpName($empName);
            $records['0']->setDepartment($department);
            $records['0']->setSalary($salary);
            $records['0']->setProfilePic($profile);

            $empDesignation = $this->entityManager->getRepository(Designations::class)->find(
                (int)$designation
            );
            $records['0']->setDesignation($empDesignation);

            if($boss === null)
            {
                $records['0']->setBoss(NULL);
            }
            else
            {
                $newBoss = $this->entityManager->getRepository(Employees::class)->find(
                    (int)$boss
                );
                $records['0']->setBoss($newBoss);
            }
            $this->entityManager->persist($records['0']);
            $this->entityManager->flush();

            $user = $this->entityManager->createQueryBuilder();
            $user->select('u')
                ->from('App:User', 'u')
                ->where('u.employee= :empId')
                ->setParameter('empId', $empId);
            $empUser = $user->getQuery()->getResult();
            if($empUser !== null)
            {
                if($empDesignation->getId() == 2)
                {
                    $empUser['0']->setRoles(["ROLE_USER", "ROLE_ADMIN"]);
                }
                else{
                    $empUser['0']->setRoles(["ROLE_USER"]);
                }
                $empUser['0']->setEmail($email);
                if($empUser['0']->getPassword() === $password)
                {
                    $empUser['0']->setPassword($password);
                }
                else
                {
                    $empUser['0']->setPassword($passwordEncoder->encodePassword(
                        $empUser['0'],
                        $password
                    ));
                }

            }
            $this->entityManager->persist($empUser['0']);
            $this->entityManager->flush();


        }


    }

    // /**
    //  * @return Employees[] Returns an array of Employees objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */


//    public function findOneBySomeField(int $value): ?Employees
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.id = :emp_id')
//            ->setParameter('emp_id', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


    public function insertEmp($name, $department, $salary, $image, $designation, $boss, $email, $password, UserPasswordEncoderInterface $passwordEncoder)
    {

        $employee = new Employees();
        $user = new User();

        $employee->setEmpName($name);
        $employee->setDepartment($department);
        $employee->setSalary($salary);
        $employee->setProfilePic($image);
        $empDesignation = $this->entityManager->getRepository(Designations::class)->find(
            (int)$designation
        );
        $employee->setDesignation($empDesignation);

        if($boss == "")
        {
            $employee->setBoss(NULL);
        }
        else
        {
            $boss = $this->entityManager->getRepository(Employees::class)->find(
                (int)$boss
            );
            $employee->setBoss($boss);
        }
        $this->entityManager->persist($employee);
        $this->entityManager->flush();

        $user->setEmail($email);
        $user->setPassword($passwordEncoder->encodePassword(
            $user,
            $password
        ));
        if($empDesignation->getId() == 2)
        {
            $user->setRoles(["ROLE_USER", "ROLE_ADMIN"]);
        }
        else{
            $user->setRoles(["ROLE_USER"]);
        }
        $user->setEmployee($employee);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
//
    }
}
