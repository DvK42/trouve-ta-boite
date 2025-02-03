<?php

namespace App\Form;

use App\Entity\Sector;
use App\Entity\Company;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegisterCompanyStep1FormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de l\'entreprise',
                'required' => true,
            ])
            ->add('sector', EntityType::class, [
                'class' => Sector::class,
                'choice_label' => 'name',
                'label' => 'Secteur d\'activité',
                'placeholder' => 'Choisir un secteur',
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email de l\'entreprise',
                'required' => true,
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'required' => true,
                'mapped' => false,
            ])
            ->add('location', TextType::class, [
                'label' => 'Ville',
                'required' => false,
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'required' => false,
            ])
            ->add('addressComplement', TextType::class, [
                'label' => 'Complément',
                'required' => false,
            ])
            ->add('postalCode', TextType::class, [
                'label' => 'Code postal',
                'required' => false,
            ])
            ->add('employeeCount', IntegerType::class, [
                'label' => 'Nombre d\'employés',
                'required' => false,
            ])
            ->add('yearFounded', IntegerType::class, [
                'label' => 'Année de création',
                'required' => false,
            ])
            ->add('logo', FileType::class, [
                'label' => 'Logo de l\'entreprise',
                'required' => false,
                'attr' => [
                    'accept' => 'image/*',
                    'class' => 'border input-apply',
                ],
                'constraints' => [
                    new Image([
                        'mimeTypes' => ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
                        'mimeTypesMessage' => 'Seules les images au format JPEG, PNG, GIF ou WEBP sont autorisées.',
                        'maxSize' => '2M',
                        'maxSizeMessage' => 'L’image ne doit pas dépasser 2 Mo.',
                    ]),
                ],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}
