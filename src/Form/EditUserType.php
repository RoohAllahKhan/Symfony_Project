<?php


namespace App\Form;


use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditUserType extends AbstractType
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
                'required'=> false,
                'attr'=>array(
                    'placeholder' => 'Password'
                )
            ))
            ->add('profile', FileType::class, array(
                'label'=>false,
                'required' => false,
                'mapped'=>false,
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
        $resolver->setDefaults([
            'data_class'=> User::class,
            'validation_groups' => ['registraion', 'Default'],
        ]);
    }


}