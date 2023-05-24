<?php

namespace App\Controller;

use App\Entity\Team;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/team')]
class TeamController extends AbstractController
{
    #[Route('/', name: 'app_team_index')]
    public function index(): Response
    {
        return $this->render('team/index.html.twig');
    }

    #[Route('/{id}', name: 'app_team_show')]
    public function show(Team $team) : Response
    {
        return $this->render('team/show.html.twig', [
            'team' => $team,
        ]);
    }
}
