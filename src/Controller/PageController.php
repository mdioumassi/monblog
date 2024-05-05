<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PageController extends AbstractController
{
    #[Route('/page/{slug}', name: 'show_page')]
    public function show(): Response
    {
        return $this->render('page/show.html.twig');
    }
}