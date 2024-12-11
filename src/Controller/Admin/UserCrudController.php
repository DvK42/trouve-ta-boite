<?php

namespace App\Controller\Admin;

use App\Entity\Admin;
use App\Entity\Company;
use App\Entity\Student;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Utilisateur') // Label pour une entité individuelle
            ->setEntityLabelInPlural('Utilisateurs') // Label pour la liste d'entités
            ->setPageTitle(Crud::PAGE_INDEX, 'Liste des Utilisateurs') // Titre de la page d'index
            ->setPageTitle(Crud::PAGE_NEW, 'Créer un Utilisateur') // Titre de la page de création
            ->setPageTitle(Crud::PAGE_EDIT, 'Modifier un Utilisateur') // Titre de la page d'édition
            ->setPageTitle(Crud::PAGE_DETAIL, 'Détail de l\'Utilisateur'); // Titre de la page de détail
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('displayName', 'Nom')->formatValue(function ($value, $entity) {
                return $entity->getDisplayName();
            }),
            TextField::new('email', 'Email'),
            TextField::new('userType', 'Type d\'utilisateur')->formatValue(function ($value, $entity) {
                return $entity->getUserTypeString();
            }),
        ];
    }

}
