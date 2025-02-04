<?php

namespace App\Form;

use App\Entity\Sector;
use App\Entity\Company;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CompanyUpdateFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
                'mapped' => false,
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
            ->add('catchPhrase', TextType::class, [
                'label' => 'Phrase d\'accroche',
                'required' => false,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description de l\'entreprise',
                'required' => false,
                'attr' => [
                    'rows' => 5,
                    'placeholder' => 'Décrivez votre entreprise...',
                ],
            ])
            ->add('websiteUrl', UrlType::class, [
                'label' => 'Site web',
                'required' => false,
            ])
            ->add('phone', TextType::class, [
                'label' => 'Téléphone',
                'required' => false,
            ])
            ->add('contactEmail', EmailType::class, [
                'label' => 'Email de contact',
                'required' => false,
            ])            ->add('address')
            ->add('addressComplement', TextType::class, [
                'label' => 'Complément d’adresse',
                'required' => false,
            ])
            ->add('postalCode', TextType::class, [
                'label' => 'Code postal',
                'required' => false,
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
