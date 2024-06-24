<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PageController extends AbstractController
{
    #[Route('/legal-mention', name: 'legal_mention')]
    public function index(): Response
    {
        return $this->render('page/legal_mention.html.twig');
    }
}
