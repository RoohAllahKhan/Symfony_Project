<?php


namespace App\Form;


use App\Entity\Attendance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use function Sodium\add;


class AttendanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder
            ->add('timeIn', TextType::class, array(
                'constraints' => [
                    new NotBlank(),
                ],
                'label'=>false,
                'attr'=>array(
                    'readonly'=>true,
                    'placeholder' => 'HH:MM',
                ),
            ))
            ->add('timeOut', TextType::class, array(
                'label'=>false,
                'attr'=>array(
                    'readonly'=>true,
                    'placeholder' => 'HH:MM',
                )
            ));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'=> Attendance::class
        ]);
    }


}