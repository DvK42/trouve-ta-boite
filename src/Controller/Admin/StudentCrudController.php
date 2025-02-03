<?php

namespace App\Controller\Admin;

use App\Entity\Student;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class StudentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Student::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Etudiant')
            ->setEntityLabelInPlural('Etudiants') 
            ->setPageTitle(Crud::PAGE_INDEX, 'Liste des Etudiants') 
            ->setPageTitle(Crud::PAGE_NEW, 'Créer un(e) Etudiant(e)') 
            ->setPageTitle(Crud::PAGE_EDIT, 'Modifier un(e) Etudiant(e)') 
            ->setPageTitle(Crud::PAGE_DETAIL, 'Détail de l\'Etudiant(e)'); 
    }


        public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('firstName', 'Prénom'),
            TextField::new('lastName', 'Nom'),
            TextField::new('email', 'Email'),
            CollectionField::new('applications', 'Postulations')
                ->onlyOnDetail()
                ->setTemplatePath('admin/fields/applications_list.html.twig'),
            CollectionField::new('skills', 'Compétences')
                ->onlyOnDetail()
                ->setTemplatePath('admin/fields/skills_list.html.twig'),
            TextField::new('education', 'Niveau d\'étude'),
            ChoiceField::new('gender', 'Genre')
                ->setChoices([
                    'Homme' => 'homme',
                    'Femme' => 'femme',
                    'Autre' => 'autre',
                ]),
            DateField::new('dateOfBirth', 'Date de naissance'),
            TextField::new('phone', 'Téléphone')->hideOnIndex(),
            TextField::new('address', 'Adresse')->hideOnIndex(),
            TextField::new('addressComplement', 'Complément d\'adresse')->hideOnIndex(),
            TextField::new('city', 'Ville'),
            TextField::new('postalCode', 'Code postal')->hideOnIndex(),
            TextField::new('portfolioUrl', 'Portfolio')->hideOnIndex(),
            BooleanField::new('isDriver', 'Permis de conduire')->hideOnIndex(),
            BooleanField::new('isHandicap', 'En situation de handicap')->hideOnIndex(),
        ];
    }
    
    public function configureActions(Actions $actions): Actions
{
    return $actions
        ->add(Crud::PAGE_INDEX, Action::DETAIL);
}

}
