<?php

namespace App\Form;

use App\Entity\Offer;
use App\Entity\Skill;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class OfferFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de l\'offre',
                'required' => true,
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Stage' => 'stage',
                    'Alternance' => 'alternance',
                ],
                'label' => 'Type d\'offre',
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description de l\'offre',
                'required' => true,
                'attr' => [
                    'rows' => 5,
                    'placeholder' => 'Décrivez les responsabilités, les compétences requises, etc.',
                ],
            ])
            ->add('startDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de début',
                'required' => true,
            ])
            ->add('endDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de fin',
                'required' => true,
            ])
            ->add('maxApplyDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date limite de candidature',
                'required' => true,
            ])
            ->add('salary', IntegerType::class, [
                'label' => 'Salaire proposé (€)',
                'required' => false,
                'attr' => ['placeholder' => 'Facultatif'],
            ])
            ->add('missionList', HiddenType::class, [
                'mapped' => false,
            ])
            ->add('profileSearchedList', HiddenType::class, [
                'mapped' => false,
            ])
            ->add('skills', EntityType::class, [
                'class' => Skill::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Compétences requises',
                'required' => false,
                'attr' => [
                    'class' => 'grid grid-cols-2 md:grid-cols-3 gap-4',
                ],
            ])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Catégories',
            ]);
    }
    
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offer::class,
        ]);
    }
}
