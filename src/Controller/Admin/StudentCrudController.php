<?php

namespace App\Controller\Admin;

use App\Entity\Student;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class StudentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Student::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Etudiants') // Label pour une entité individuelle
            ->setEntityLabelInPlural('Etudiants') // Label pour la liste d'entités
            ->setPageTitle(Crud::PAGE_INDEX, 'Liste des Etudiants') // Titre de la page d'index
            ->setPageTitle(Crud::PAGE_NEW, 'Créer une Etudiants') // Titre de la page de création
            ->setPageTitle(Crud::PAGE_EDIT, 'Modifier une Etudiants') // Titre de la page d'édition
            ->setPageTitle(Crud::PAGE_DETAIL, 'Détail de l\'Etudiants'); // Titre de la page de détail
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('first_name', 'Prénom'),
            TextField::new('last_name', 'Nom'),
            TextField::new('email', 'Email'),
            TextField::new('education', 'Niveau d\'étude'),
        ];
    }
}
