<?php


namespace App\Service;


use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserService extends AbstractController
{

    private $userRepository;

    private $entityManager;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager)
    {

        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    public function findUser($empId)
    {
        return $this->entityManager->getRepository(User::class)->find($empId);
    }
}