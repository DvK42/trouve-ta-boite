<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ErrorController extends AbstractController
{
    #[Route('/error/403', name: 'error_403')]
    public function error403(): Response
    {
        return $this->render('errors/403.html.twig', [
            'message' => 'Vous n\'êtes pas autorisé à accéder à cette page.',
        ]);
    }
}
