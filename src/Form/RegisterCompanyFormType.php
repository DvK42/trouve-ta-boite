<?php

namespace App\Form;

use App\Entity\Company;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegisterCompanyFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, ['label' => 'Email'])
            ->add('password', PasswordType::class, ['label' => 'Mot de passe'])
            ->add('name', TextType::class, ['label' => 'Nom de l\'entreprise'])
            ->add('location', TextType::class, ['label' => 'Ville'])
            ->add('sector', ChoiceType::class, [
                'choices' => [
                    'Choisir un secteur d\'activité' => null,
                    'Informatique' => 'Informatique',
                    'Marketing' => 'Marketing',
                ],
                'label' => 'Secteur d\'activité',
            ])
            ->add('logo', FileType::class, [
                'label' => 'Logo de l\'entreprise',
                'required' => false,
                'attr' => [
                    'accept' => 'image/*',
                    'class' => 'border input-apply',
                ],
            ]);
    }

    // public function configureOptions(OptionsResolver $resolver): void
    // {
    //     $resolver->setDefaults([
    //         'data_class' => Company::class,
    //     ]);
    // }
}
