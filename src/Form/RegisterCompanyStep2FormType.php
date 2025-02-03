<?php

namespace App\Form;

use App\Entity\Sector;
use App\Entity\Company;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RegisterCompanyStep2FormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}
