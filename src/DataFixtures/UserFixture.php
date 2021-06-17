<?php

namespace App\DataFixtures;

use App\Entity\Employees;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class UserFixture extends BaseFixture
{
    public function loadData(ObjectManager $manager)
    {
        $this->createMany(1, 'employees', function ($i){
           $employee = new Employees();
           $employee->setEmpName('Rooh');
           $employee->setDepartment('Engineering');
           $employee->setProfilePic('abc');
           $employee->setSalary(65000);

           return $employee;
        });
//        $this->createMany(1, 'main_users', function ($i){
//           $user = new User();
//           $user->setEmail(sprintf('roohallahkhan20@gmail.com', $i));
//           $user->se
//        });
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
