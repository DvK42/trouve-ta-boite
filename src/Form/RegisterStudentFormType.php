<?php

namespace App\Form;

use App\Entity\Skill;
use App\Entity\Student;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterStudentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('password')
            // ->add('roles')
            ->add('firstName')
            ->add('lastName')
            ->add('education')
            ->add('skills', EntityType::class, [
                'class' => Skill::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    // public function configureOptions(OptionsResolver $resolver): void
    // {
    //     $resolver->setDefaults([
    //         'data_class' => Student::class,
    //     ]);
    // }
}
