<?php

namespace App\Controller\Api;

use App\DataHandler\InputHandler\TeamInputHandler;
use App\Dto\IntputDTO\TeamInput;
use App\Entity\Team;
use App\Repository\TeamRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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

    #[Route('/', name: 'api_team_list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        return $this->json($this->repository->findAll());
    }

    #[Route('/', name: 'api_team_create', methods: ['POST'])]
    public function createTeam(
        Request $request,
        TeamInputHandler $handler,
        ValidatorInterface $validator
    ): Response
    {
        /** @var TeamInput $input */
        $input = $this->serializer->deserialize($request->getContent(), TeamInput::class, 'json');
        $errors = $validator->validate($input);
        if (count($errors)) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }
        $team = $handler->handle($input);

        return $this->json($team, Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'api_team_update', methods: ['PUT'])]
    public function updateTeam(
        Request $request,
        Team $team,
        TeamInputHandler $handler,
        ValidatorInterface $validator,
    ): Response
    {
        /** @var TeamInput $input */
        $input = $this->serializer->deserialize($request->getContent(), TeamInput::class, 'json');
        $errors = $validator->validate($input);
        if (count($errors)) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }
        $team = $handler->handle($input, $team);

        return $this->json($team, Response::HTTP_OK);
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