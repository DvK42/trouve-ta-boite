<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ErrorController extends AbstractController
{
    #[Route('/403', name: 'error_403')]
    public function error403(): Response
    {
        return $this->render('errors/403.html.twig', [
            'message' => 'Vous n\'êtes pas autorisé à accéder à cette page.',
        ]);
    }

    #[Route('/404', name: 'error_404')]
    public function error404(): Response
    {
        return $this->render('errors/404.html.twig', [
            'message' => 'La page que vous recherchez est introuvable.',
        ]);
    }
}
