<?php

namespace App\Form;

use App\Entity\Skill;
use App\Entity\Student;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegisterStudentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, ['label' => 'Email'])
            ->add('password', PasswordType::class, ['label' => 'Mot de passe'])
            ->add('firstName', TextType::class, ['label' => 'Prénom'])
            ->add('lastName', TextType::class, ['label' => 'Nom'])
            ->add('education', ChoiceType::class, [
                'choices' => [
                    '' => null,
                    '4ème' => '4ème',
                    '3ème' => '3ème',
                    'Seconde' => 'Seconde',
                    'Première' => 'Première',
                    'Terminale' => 'Terminale',
                    'Bac' => 'Bac',
                    'Bac +1' => 'Bac +1',
                    'Bac +2' => 'Bac +2',
                    'Bac +3' => 'Bac +3',
                    'Bac +4' => 'Bac +4',
                    'Bac +5' => 'Bac +5',
                    'Bac +6' => 'Bac +6',
                    'Bac +7' => 'Bac +7',
                ],
                'placeholder' => 'Choisissez votre niveau',
                'label' => 'Niveau actuel',
                'required' => true,
                ])
                // ->add('skills', EntityType::class, [
                //     'class' => Skill::class, // L'entité qui contient les compétences
                //     'choice_label' => 'name', // L'attribut de l'entité utilisé comme label
                //     'multiple' => true, // Permet la sélection multiple
                //     'expanded' => false, // Menu déroulant multiple
                //     'label' => 'Compétences',
                //     'required' => false, // Rendre ce champ optionnel
                //     'attr' => [
                //         'class' => 'border input-apply w-full', // Classes CSS personnalisées
                //     ],
                // ])
        ;
    }

    // public function configureOptions(OptionsResolver $resolver): void
    // {
    //     $resolver->setDefaults([
    //         'data_class' => Student::class,
    //     ]);
    // }
}
