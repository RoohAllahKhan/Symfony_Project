<?php

namespace App\Controller;

use App\Entity\Attendance;
use App\Entity\Designations;
use App\Entity\Employees;
use App\Entity\User;
use App\Form\AddEmployeeType;
use App\Form\AddUserType;
use App\Form\AttendanceType;
use App\Form\EditUserType;
use App\Service\EmployeeService;
use App\Service\MarkAttendanceService;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/hradmin", name="admin_")
 * @IsGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function adminHomepage(Request $request)
    {
        return $this->render('admin/adminpanel.html.twig');

    }

    /**
     * @Route("/monthReport/{month}-{year}", name="monthlyReport")
     */
    public function monthlyStatus(MarkAttendanceService $attendanceService, $month, $year)
    {
        $monthReport = $attendanceService->monthReport($month, $year);
        return $this->render('admin/monthlyReport.html.twig', [
            'monthRecord'=>$monthReport,
        ]);

    }

    /**
     * @Route("/currAttendance", name="currAttendance")
     */
    public function currentAttendance(MarkAttendanceService $attendanceService)
    {
        $today = date("d/m/Y");
        $attRecords = $attendanceService->todayAttendance($today);
        return $this->render('admin/currentAttendance.html.twig', [
           'currAttendance'=> $attRecords,
        ]);

    }

    /**
     * @Route("/empId={empId}", name="delEmp")
     */
    public function delEmployee($empId, EmployeeService $employeeService)
    {
        $employeeService->delete_Employee($empId);

        return $this->redirectToRoute('admin_showEmployees');

    }
    /**
     * @Route("/allEmployees", name="showEmployees")
     */
    public function showEmployees(EntityManagerInterface $entityManager, EmployeeService $employeeService)
    {
        $allEmployees = $employeeService->getAllEmployees();

        return $this->render('admin/allemployees.html.twig', [
            'employees'=>$allEmployees,
        ]);

    }

    /**
     * @Route("/editEmployee/{empId}", name="edit_employee")
     */
    public function editPage(Request $request, $empId, UserPasswordEncoderInterface $passwordEncoder, EmployeeService $employeeService, UserService $userService)
    {
//        dd($empId);
        $user = new User();
//        $user = new AddUserType();
        $employee = new Employees();
        $user->getEmployees()->add($employee);
        $editform = $this->createForm(EditUserType::class, $user, ['validation_groups' => ['Default']]);
        $emp = $userService->findUser($empId);
        $priorEmpData = $emp;
//        dd($emp->getEmployee()->getProfilePic());
//        $emp = $this->getUser();
        $editform->handleRequest($request);
//        dd($form->getData()->getEmployees());
        if($editform->isSubmitted() && $editform->isValid()) {
//            dump($this->getUser());
            $employeeService->setEmpUpdation($priorEmpData, $editform, $passwordEncoder);

            $this->addFlash('empUpdated', 'Employee is Updated Successfully!');
            return $this->redirectToRoute('admin_showEmployees');

        }
//        dump($form->createView());die();
        return $this->render('forms/editEmp.html.twig', [
            'editForm' => $editform->createView(),
            "employee" => $emp,
        ]);
    }
    /**
     * @Route("/addemployee", name="employee_page")
     */
    public function formEmployee(Request $request, UserPasswordEncoderInterface $passwordEncoder, EmployeeService $addEmp)
    {
//        $this->denyAccessUnlessGranted("ROLE_ADMIN");


        $user = new User();
        $employee = new Employees();


        $user->getEmployees()->add($employee);
//        $user = $this->getDoctrine()->getRepository('App:Employees')->find(1000);
//        dump($user->getDesignation()->getDesignationName(), $employee);die();

        $form = $this->createForm(AddUserType::class, $user, ['validation_groups' => ['registration', 'Default']]);
//        dd($form);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
//            dd($form->getData()->getEmployees()['0']->getBoss());
//dd($form->getViewData());
            $addEmp->setEmployee($form, $passwordEncoder);
//            if($form->getData()->getEmployees()['0']->getBoss() == null)
//            {
//                            dd($form->getData()->getEmployees()['0']->getEmpName());
//
//            }
            $this->addFlash('success', 'Employee is Added Successfully!');
            return $this->redirectToRoute('admin_employee_page');

        }
//        dump($form->createView());die();
        return $this->render('forms/addemp.html.twig', [
           'form' => $form->createView(),
        ]);

    }

}
