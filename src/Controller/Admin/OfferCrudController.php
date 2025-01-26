<?php

namespace App\Controller\Admin;

use App\Entity\Offer;
use App\Controller\Admin\CompanyCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;


class OfferCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Offer::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('company', 'Entreprise')
            ->setCrudController(CompanyCrudController::class)
            ->autocomplete(),
            
            TextField::new('name', 'Nom de l\'offre'),
            ChoiceField::new('type', 'Type d\'offre')
                ->setChoices([
                    'Stage' => 'stage',
                    'Alternance' => 'alternance',
                ]),
            TextField::new('description', 'Description')->hideOnIndex(),
            DateField::new('startDate', 'Date de début'),
            DateField::new('endDate', 'Date de fin'),
            MoneyField::new('salary', 'Salaire')->setCurrency('EUR')->hideOnIndex(),

            AssociationField::new('categories', 'Catégories') 
                ->setFormTypeOption('by_reference', false), 

            CollectionField::new('missionList', 'Missions')
            ->setEntryType(TextType::class)
            ->onlyOnForms(),

            CollectionField::new('profileSearchedList', 'Profil recherché')
            ->setEntryType(TextType::class)
            ->onlyOnForms(),
        ];
    }
}
