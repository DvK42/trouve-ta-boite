<?php

namespace App\Form;

use App\Entity\Skill;
use App\Entity\Student;
use App\Enum\EducationLevel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegisterStudentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'required' => true,
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'required' => true,
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'required' => true,
            ])
            ->add('gender', ChoiceType::class, [
                'choices' => [
                    'Homme' => 'homme',
                    'Femme' => 'femme',
                    'Autre' => 'autre',
                ],
                'label' => 'Genre',
                'expanded' => false, 
                'required' => true,
            ])
            ->add('dateOfBirth', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de naissance',
                'required' => false,
            ])
            ->add('phone', TelType::class, [
                'label' => 'Téléphone mobile',
                'required' => false,
            ])
            ->add('studyPlace', TextType::class, [
                'label' => 'Établissement',
                'required' => false,
            ])
            ->add('education', ChoiceType::class, [
                'choices' => array_combine(EducationLevel::LEVELS, EducationLevel::LEVELS),
                'placeholder' => 'Choisissez votre niveau',
                'label' => 'Niveau actuel',
                'required' => true,
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'required' => false,
            ])
            ->add('addressComplement', TextType::class, [
                'label' => 'Complément d’adresse',
                'required' => false,
            ])
            ->add('postalCode', TextType::class, [
                'label' => 'Code postal',
                'required' => false,
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'required' => false,
            ])
            ->add('portfolioUrl', TextType::class, [
                'label' => 'Site web personnel (portfolio)',
                'required' => false,
            ])
            ->add('isDriver', CheckboxType::class, [
                'label' => 'Je possède un permis de conduire',
                'required' => false,
            ])
            ->add('isHandicap', CheckboxType::class, [
                'label' => 'J’ai une forme de handicap',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_token' => 'authenticate', 
        ]);
    }
}
