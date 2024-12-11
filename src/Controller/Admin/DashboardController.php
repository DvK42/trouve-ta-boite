<?php

namespace App\Controller\Admin;

use App\Controller\UserController;
use App\Entity\Application;
use App\Entity\Category;
use App\Entity\Company;
use App\Entity\Document;
use App\Entity\Message;
use App\Entity\Notification;
use App\Entity\Offer;
use App\Entity\Skill;
use App\Entity\Student;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        return $this->render('templates/user/index.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Trouve Ta Boite');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section('Données');
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', User::class);
        yield MenuItem::linkToCrud('Entreprises', 'fas fa-users', Company::class);
        yield MenuItem::linkToCrud('Etudiants', 'fas fa-users', Student::class);
        yield MenuItem::linkToCrud('Offres', 'fas fa-users', Offer::class);
        yield MenuItem::linkToCrud('Candidatures', 'fas fa-users', Application::class);
        yield MenuItem::linkToCrud('Notifications', 'fas fa-users', Notification::class);
        yield MenuItem::linkToCrud('Messages', 'fas fa-users', Message::class);
        yield MenuItem::linkToCrud('Documents', 'fas fa-users', Document::class);
        yield MenuItem::section('Listes');
        yield MenuItem::linkToCrud('Categories', 'fas fa-users', Category::class);
        yield MenuItem::linkToCrud('Skill', 'fas fa-users', Skill::class);
    }
}
