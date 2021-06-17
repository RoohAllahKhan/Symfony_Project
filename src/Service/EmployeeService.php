<?php


namespace App\Service;


use App\Entity\Employees;
use App\Entity\User;
use App\Repository\EmployeesRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class EmployeeService extends AbstractController
{
    private $er;

    private $entityManager;

    private $userRepository;

    public function __construct(EmployeesRepository $er , UserRepository $userRepository, EntityManagerInterface $entityManager){

        $this->er = $er;
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }

    public function delete_Employee($empId)
    {
//        $employee = $this->entityManager->getRepository(Employees::class)->find($empId);
        $this->entityManager->getRepository(Employees::class)->delEmployee($empId);

    }
    public function findEmployee($empId)
    {
        return $this->entityManager->getRepository(Employees::class)->find($empId);
    }

    public function getAllEmployees()
    {
        return $this->entityManager->getRepository(Employees::class)->findAll();
    }
    public function setEmpUpdation($priorData, $newData, $passwordEncoder)
    {
//        $newData->getEmployees()['0']->get
//dd($newData);
//        dd($priorData->getEmployee()->getId());
        $employeeId = $priorData->getEmployee()->getId();
        $empName = $newData->getData()->getEmployees()['0']->getEmpName();
        $department = $newData->getData()->getEmployees()['0']->getDepartment();
        $salary = $newData->getData()->getEmployees()['0']->getSalary();
        $designation = $newData->getData()->getEmployees()['0']->getDesignation()->getId();
        if($newData->getData()->getEmployees()['0']->getBoss() === null)
        {
            $boss = null;
        }
        else
        {
            $boss = $newData->getData()->getEmployees()['0']->getBoss()->getId();
        }
        $profile = $newData->get('profile')->getData();
        if($newData->get('profile')->getData() === null)
        {
                $profile = $priorData->getEmployee()->getProfilePic();
        }
        else
        {
            $file = $newData->get('profile')->getData();
            $filename = md5(uniqid()). '.'. $file->guessExtension();
            $uploads_directory = $this->getParameter('uploads_directory');
            $file->move(
                $uploads_directory,
                $filename
            );
            $profile = $filename;
        }
        $email = $newData->getData()->getEmail();
        if($newData->getData()->getPassword() === null)
        {
            $password = $priorData->getPassword();
        }
        else
        {
            $password = $newData->getData()->getPassword();
        }
        $this->er->updateEmployee($employeeId, $empName, $department, $salary, $profile, $designation, $boss, $email, $password, $passwordEncoder);
//        $this->userRepository


//        dd($profile);
//        dump($empName);
//        dump($department);
//        dump($designation);
//        dump($salary);
//        dump($boss);
//        dump($profile);
//        dump($email);
//        dump($password);
//        die;

    }
    public function setEmployee($form, UserPasswordEncoder $passwordEncoder){
//        dd($form['profile']->getData());
//        dd($form->get('profile')->getData());
        $empName = $form->getData()->getEmployees()['0']->getEmpName();
        $department = $form->getData()->getEmployees()['0']->getDepartment();
        $salary = $form->getData()->getEmployees()['0']->getSalary();
        $file = $form->get('profile')->getData();
        $filename = md5(uniqid()). '.'. $file->guessExtension();
        $uploads_directory = $this->getParameter('uploads_directory');
        $file->move(
            $uploads_directory,
            $filename
        );
        $designation = $form->getData()->getEmployees()['0']->getDesignation()->getId();
        if($form->getData()->getEmployees()['0']->getBoss() == null)
        {
                $boss = null;
        }
        else{
               $boss = $form->getData()->getEmployees()['0']->getBoss()->getId();
        }

        $email = $form->getData()->getEmail();
        $password = $form->getData()->getPassword();
        $this->er->insertEmp($empName, $department, $salary, $filename, $designation, $boss, $email, $password, $passwordEncoder);

    }

}