<?php

namespace App\Controller;

use App\Repository\BidRepository;
use App\Repository\PlayerTeamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    public function __construct(
        private BidRepository $bidRepository,
        private PlayerTeamRepository $playerTeamRepository,
    )
    {

    }
    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    #[Route('/last-bids', name: 'app_index_lastbids')]
    public function lastBidsAction(Request $request)
    {

    }
}
