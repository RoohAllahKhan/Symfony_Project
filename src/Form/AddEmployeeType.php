<?php


namespace App\Form;


use App\Entity\Designations;
use App\Entity\Employees;
use App\Entity\User;
use App\Repository\EmployeesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddEmployeeType extends AbstractType
{

    private $entityManager;


    public function __construct(EntityManagerInterface $entityManager )
    {

        $this->entityManager = $entityManager;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('empName', TextType::class, array(
                'label'=>false,
                'attr'=>array(
                    'placeholder' => 'Enter name'
                )
        ))
        ->add('department', TextType::class, array(
                'label'=>false,
                'attr'=>array(
                    'placeholder' => 'Department'
                )
        ))
        ->add('salary', NumberType::class, array(
            'label'=>false,
            'attr'=>array(
                'placeholder' => 'Salary'
            )
        ))
        ->add('designation', EntityType::class, [
            'label' => false,
             'class' => Designations::class,
             'query_builder' => function(EntityRepository $er){
                $qb = $er->createQueryBuilder('d');
                return $qb;
             },
        ])
        ->add('boss', EntityType::class, [
            'label' => false,
            'class' => Employees::class,
            'query_builder' => function (EntityRepository $er){
                $qb = $er->createQueryBuilder('u');
                return $qb
                    ->where('u.designation = :designation')
                    ->setParameter('designation', 1);
            },
            'choice_label' => 'empName',
            'invalid_message' => 'You have done something wrong in inspect element'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        //tag
        $resolver->setDefaults([
           'data_class'=> Employees::class
        ]);

    }
}