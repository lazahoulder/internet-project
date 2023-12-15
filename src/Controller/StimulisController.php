<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StimulisController extends AbstractController
{
    #[Route('/stimulis', name: 'app_stimulis')]
    public function index(): Response
    {
        return $this->render('stimulis/index.html.twig', [
            'controller_name' => 'StimulisController',
        ]);
    }
}
