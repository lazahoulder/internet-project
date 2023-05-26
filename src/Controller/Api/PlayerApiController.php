<?php

namespace App\Controller\Api;

use App\Repository\PlayerRepository;
use App\Repository\PlayerTeamRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('api/players')]
class PlayerApiController extends BaseApiController
{
    public function __construct(
        SerializerInterface $serializer,
        PlayerTeamRepository $playerTeamRepository,
        PlayerRepository $playerRepository,
    )
    {
        parent::__construct($serializer);
    }

    #[Route('/', name: 'api_players_list', methods: ['GET'])]
    public function list(Request $request)
    {

    }

    #[Route('/', name: 'api_players_list_inactive', methods: ['GET'])]
    public function listInactive(Request $request)
    {

    }

    public function hire(Request $request, string $playerId)
    {

    }
}