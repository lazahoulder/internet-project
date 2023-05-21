<?php

namespace App\Controller\Api;

use App\Entity\Team;
use App\Repository\TeamRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('api/team')]
class TeamsApi extends BaseApiController
{
    public function __construct(
        SerializerInterface $serializer,
        private TeamRepository $repository
    )
    {
        parent::__construct($serializer);
    }

    #[Route('/', name: 'api_team_list')]
    public function list(Request $request): JsonResponse
    {
        return $this->json($this->repository->findAll());
    }

    #[Route('/{id}', name: 'api_team_delete', methods: ['DELETE'])]
    public function createTeam(Request $request)
    {
        $this->repository->remove($team, true);
        $response = new Response();
        $response->setStatusCode(Response::HTTP_NO_CONTENT);

        return $response;
    }

    #[Route('/{id}', name: 'api_team_delete', methods: ['DELETE'])]
    public function removeTeam(Team $team)
    {
        $this->repository->remove($team, true);
        $response = new Response();
        $response->setStatusCode(Response::HTTP_NO_CONTENT);

        return $response;
    }
}