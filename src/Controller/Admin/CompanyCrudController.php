<?php

namespace App\Controller\Admin;

use App\Entity\Company;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CompanyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Company::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Entreprise') // Label pour une entité individuelle
            ->setEntityLabelInPlural('Entreprises') // Label pour la liste d'entités
            ->setPageTitle(Crud::PAGE_INDEX, 'Liste des Entreprises') // Titre de la page d'index
            ->setPageTitle(Crud::PAGE_NEW, 'Créer une Entreprise') // Titre de la page de création
            ->setPageTitle(Crud::PAGE_EDIT, 'Modifier une Entreprise') // Titre de la page d'édition
            ->setPageTitle(Crud::PAGE_DETAIL, 'Détail de l\'Entreprise'); // Titre de la page de détail
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('name', 'Nom'),
            TextField::new('email', 'Email'),
            TextField::new('sector', 'Secteur d\'activité'),
            TextField::new('description', 'Description'),
        ];
    }
}
