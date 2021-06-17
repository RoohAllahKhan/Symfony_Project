<?php


namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AttendanceController
{
    /**
     * @Route("/attendance", name="app_attendance")
     */
    public function markAttendance()
    {
        return new Response("Hello World");
    }

}