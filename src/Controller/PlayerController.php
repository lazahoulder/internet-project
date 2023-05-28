<?php

namespace App\Controller;

use App\Entity\Player;
use App\Form\Player1Type;
use App\Repository\PlayerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/player')]
class PlayerController extends AbstractController
{
    #[Route('/', name: 'app_player_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('player/index.html.twig');
    }

    #[Route('/{id}', name: 'app_player_show', methods: ['GET'])]
    public function show(Player $player)
    {
        return $this->render('player/show.html.twig', ['player' => $player]);
    }
}
