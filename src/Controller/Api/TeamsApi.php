<?php

namespace App\Controller\Api;

use App\DataTransformer\InputHandler\PlayerTeamInputHandler;
use App\DataTransformer\InputHandler\TeamInputHandler;
use App\DataTransformer\OutputHandlar\PlayerTeamTransformer;
use App\DataTransformer\OutputHandlar\TeamOutputTranformer;
use App\Dto\IntputDTO\PlayerInput;
use App\Dto\IntputDTO\TeamInput;
use App\Entity\Player;
use App\Entity\Team;
use App\Repository\PlayerTeamRepository;
use App\Repository\TeamRepository;
use Knp\Component\Pager\PaginatorInterface;
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
        SerializerInterface           $serializer,
        private TeamRepository        $repository,
        private PaginatorInterface    $paginator,
        private PlayerTeamRepository  $playerTeamRepository,
        private PlayerTeamTransformer $playerTeamTransformer,
    )
    {
        parent::__construct($serializer);
    }

    #[Route('/', name: 'api_team_list', methods: ['GET'])]
    public function list(Request $request, TeamOutputTranformer $outputHandler): JsonResponse
    {
        $pagination = $this->paginator->paginate(
            $this->repository->findAllQB(),
            (int)$request->get('page', 1)
        );
        $data = [
            'results' => $outputHandler->listNormalize($pagination->getItems()),
            'page' => $pagination->getCurrentPageNumber(),
            'pageNumber' => ceil($pagination->getTotalItemCount() / $pagination->getItemNumberPerPage()),
        ];

        return $this->json($data, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'api_team_read', methods: ['GET'])]
    public function read(Team $team, TeamOutputTranformer $outputHandler): JsonResponse
    {
        return $this->json($outputHandler->normalize($team), Response::HTTP_OK);
    }

    #[Route('/', name: 'api_team_create', methods: ['POST'])]
    public function createTeam(
        Request              $request,
        TeamInputHandler     $handler,
        ValidatorInterface   $validator,
        TeamOutputTranformer $outputHandler
    ): Response
    {
        /** @var TeamInput $input */
        $input = $this->serializer->deserialize($request->getContent(), TeamInput::class, 'json');
        $errors = $validator->validate($input);
        if (count($errors)) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }
        $team = $handler->handle($input);

        return $this->json($outputHandler->normalize($team), Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'api_team_update', methods: ['PUT'])]
    public function updateTeam(
        Request              $request,
        Team                 $team,
        TeamInputHandler     $handler,
        ValidatorInterface   $validator,
        TeamOutputTranformer $outputHandler
    ): Response
    {
        /** @var TeamInput $input */
        $input = $this->serializer->deserialize($request->getContent(), TeamInput::class, 'json');
        $errors = $validator->validate($input);
        if (count($errors)) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }
        $team = $handler->handle($input, $team);

        return $this->json($outputHandler->normalize($team), Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'api_team_delete', methods: ['DELETE'])]
    public function removeTeam(Team $team)
    {
        $this->repository->remove($team, true);
        $response = new Response();
        $response->setStatusCode(Response::HTTP_NO_CONTENT);

        return $response;
    }

    #[Route('/{id}/players', name: 'api_team_list_player', methods: ['GET'])]
    public function listPlayer(Request $request, string $id)
    {
        $pagination = $this->paginator->paginate(
            $this->playerTeamRepository->findByTeamQuery($id),
            (int)$request->get('page', 1)
        );

        $data = [
            'results' => $this->playerTeamTransformer->listNormalize($pagination->getItems()),
            'page' => $pagination->getCurrentPageNumber(),
            'pageNumber' => ceil($pagination->getTotalItemCount() / $pagination->getItemNumberPerPage()),
            'total' => $pagination->getTotalItemCount(),
        ];

        return $this->json($data, Response::HTTP_OK);
    }

    #[Route('/{id}/players', name: 'api_team_add_player', methods: ['POST'])]
    public function addPlayer(
        Request                $request,
        string                 $id,
        ValidatorInterface     $validator,
        PlayerTeamInputHandler $playerTeamInputHandler,
    ): JsonResponse
    {
        /** @var PlayerInput $input */
        $input = $this->serializer->deserialize($request->getContent(), PlayerInput::class, 'json');
        $input->teamId = $id;

        return $this->handlePlayerInput($validator, $input, $playerTeamInputHandler);
    }

    #[Route('/{id}/players/{playerId}', name: 'api_team_edit_player', methods: ['PUT'])]
    public function editPlayer(
        Request                $request,
        string                 $id,
        string                 $playerId,
        ValidatorInterface     $validator,
        PlayerTeamInputHandler $playerTeamInputHandler,
    ): JsonResponse
    {
        /** @var PlayerInput $input */
        $input = $this->serializer->deserialize($request->getContent(), PlayerInput::class, 'json');
        $input->teamId = $id;
        $input->playerTeamId = $playerId;

        return $this->handlePlayerInput($validator, $input, $playerTeamInputHandler);
    }

    #[Route('/{id}/players/{playerId}', name: 'api_team_ell_player', methods: ['PATCH'])]
    public function sellPlayer(Request $request, Team $team)
    {

    }

    #[Route('/{id}/players/{playerId}', name: 'api_team_remove_player', methods: ['DELETE'])]
    public function deletePlayer(Request $request, Team $team)
    {

    }

    /**
     * @param ValidatorInterface $validator
     * @param PlayerInput $input
     * @param PlayerTeamInputHandler $playerTeamInputHandler
     * @return JsonResponse
     */
    private function handlePlayerInput(
        ValidatorInterface $validator,
        PlayerInput $input,
        PlayerTeamInputHandler $playerTeamInputHandler
    ): JsonResponse
    {
        $errors = $validator->validate($input);

        if (count($errors)) {
            return $this->handelError($errors);
        }

        try {
            $playerTeam = $playerTeamInputHandler->handle($input);
        } catch (\Exception $e) {
            return $this->json(
                [
                    'message' => $e->getMessage()
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return $this->json(
            $this->playerTeamTransformer->normalize($playerTeam),
            Response::HTTP_CREATED
        );
    }
}