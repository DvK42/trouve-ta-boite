<?php

namespace App\Form;

use App\Entity\Sector;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
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
            ->add('sector', EntityType::class, [
                'class' => Sector::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisir un secteur d\'activité',
                'required' => true,
                'label' => 'Secteur d\'activité',
                'attr' => [
                    'class' => 'block w-full mt-1 border-gray-300 rounded-md',
                ],
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
            ])
            ->add('employeeCount', IntegerType::class, [
                'label' => 'Nombre d\'employés',
                'attr' => ['class' => 'block w-full mt-1 border-gray-300 rounded-md'],
                'required' => false,
            ])
            ->add('yearFounded', IntegerType::class, [
                'label' => 'Année de création',
                'attr' => ['class' => 'block w-full mt-1 border-gray-300 rounded-md'],
                'required' => false,
            ])
            ->add('_csrf_token', HiddenType::class, [
                'mapped' => false,
                'data' => $options['csrf_token'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_token' => 'authenticate', 
        ]);
    }
}
