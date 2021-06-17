<?php


namespace App\Form;


use App\Entity\Employees;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    $builder
         ->add('email', EmailType::class, array(
             'label'=> false,
             'attr'=>array(
                 'placeholder' => 'Email@example.com'
             )
         ))
         ->add('password', PasswordType::class, array(
             'label'=> false,
             'attr'=>array(
                 'placeholder' => 'Password'
             )
         ))
        ->add('profile', FileType::class, array(
            'label'=>false,
            'mapped'=>false,
            'constraints' => array(
                new NotBlank(['groups'=>['registration']]),
            ),
//            'empty_data' => "",
//            'invalid_message' => 'You have done something wrong in inspect element'

        ));

        //Adding Employee(tag) to user(tasks)
        $builder->add('employees', CollectionType::class, [
            'entry_type' => AddEmployeeType::class,
            'entry_options' => ['label' => false],
            'allow_add' => true,
            'allow_delete' => true
        ]);


    }
    public function configureOptions(OptionsResolver $resolver)
    {
        //task
        $resolver->setDefaults([
            'data_class'=> User::class,
            'validation_groups' => ['Default'],
        ]);

    }
}