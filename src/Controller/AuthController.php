<?php

namespace App\Controller;

use App\Entity\Attendance;
use App\Entity\Employees;
use App\Entity\User;
use App\Form\AttendanceType;
use App\Repository\UserRepository;
use App\Service\MarkAttendanceService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends BaseController
{

    /**
     * @Route("/home", name="app_homepage")
     * @IsGranted("ROLE_USER")
     */
    public function homepage(Request $request, MarkAttendanceService $attendanceService, MarkAttendanceService $markAttendance)
    {
//        dd($this->getUser()->getEmployee()->getEmpName());
        $attendanceForm = $this->createForm(AttendanceType::class);
        $currentEmp = $this->getUser()->getEmployee();
        $em = $this->getDoctrine()->getManager();
        $emp = $this->getUser()->getEmployee()->getId();
        $date = date("d/m/Y");
        $hour = date("g");
        $records = $em->getRepository(Attendance::class)->checkAttendance($emp, $date);
        $attendanceForm->handleRequest($request);
        if($attendanceForm->isSubmitted() && $attendanceForm->isValid()) {
            if ($records == null) {
                if ($attendanceForm->getViewData()->getTimeIn() != null) {
                    $attendanceService->setAttendance($attendanceForm, $this->getUser(), $hour);
                }

            } else {
                $markAttendance->setAttendanceUpdation($records, $attendanceForm, $emp, $date, $em);

            }
                $this->addFlash('attendanceMarked', 'Attendance has been marked successfully');
                return $this->redirectToRoute('app_homepage');

        }
        return $this->render('home/homepage.html.twig', [
            'attendanceForm' => $attendanceForm->createView(),
            'employee' => $records,
            'currEmp' => $currentEmp,
        ]);
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
//        return new RedirectResponse($this->router->generate('app_homepage'));

//        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }


}
