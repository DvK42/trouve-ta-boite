<?php

namespace App\Form;

use App\Entity\Skill;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ApplicationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('updateInfo', CheckboxType::class, [
                'label' => 'Mettre à jour mes informations ',
                'required' => false,
            ])
            ->add('gender', ChoiceType::class, [
                'choices' => [
                    'Homme' => 'homme',
                    'Femme' => 'femme',
                    'Autre' => 'autre',
                ],
                'label' => 'Genre',
                'required' => true,
            ])
            ->add('firstName', TextType::class, ['label' => 'Prénom'])
            ->add('lastName', TextType::class, ['label' => 'Nom'])
            ->add('dateOfBirth', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de naissance',
            ])
            ->add('phone', TelType::class, ['label' => 'Téléphone mobile'])
            ->add('email', EmailType::class, ['label' => 'Email'])
            ->add('address', TextType::class, ['label' => 'Adresse'])
            ->add('addressComplement', TextType::class, [
                'label' => 'Complément d’adresse',
                'required' => false,
            ])
            ->add('postalCode', TextType::class, ['label' => 'Code postal'])
            ->add('city', TextType::class, ['label' => 'Ville'])
            ->add('portfolioUrl', TextType::class, [
                'label' => 'Adresse de votre site web personnel',
                'required' => false,
            ])
            ->add('isDriver', CheckboxType::class, [
                'label' => 'J’ai le permis de conduire',
                'required' => false,
            ])
            ->add('isHandicap', CheckboxType::class, [
                'label' => 'J’ai une forme de handicap',
                'required' => false,
            ])
            ->add('profilePicture', FileType::class, [
                'label' => 'Photo de profil',
                'mapped' => false,
                'required' => false,
            ])
            ->add('skills', EntityType::class, [
                'class' => Skill::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'required' => false,
                'label' => 'Compétences',
                'attr' => [
                    'id' => 'skills-select',
                    'class' => 'block w-full mt-1 border-gray-300 rounded-md',
                ],
            ])
            ->add('coverLetter', TextareaType::class, [
                'label' => 'Lettre de motivation',
                'attr' => [
                    'placeholder' => 'Rédigez votre lettre de motivation ici...',
                    'class' => 'block w-full mt-1 border-gray-300 rounded-md',
                    'rows' => 5,
                ],
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
