<?php

namespace App\Controller\Api;

use App\DataTransformer\InputHandler\PlayerTeamInputHandler;
use App\DataTransformer\InputHandler\TeamInputHandler;
use App\DataTransformer\OutputHandler\PlayerTeamTransformer;
use App\DataTransformer\OutputHandler\TeamOutputTranformer;
use App\Dto\IntputDTO\PlayerTeamInput;
use App\Dto\IntputDTO\TeamInput;
use App\Entity\Team;
use App\Repository\PlayerTeamRepository;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    use HasInputHandlerTrait;

    public function __construct(
        SerializerInterface           $serializer,
        private TeamRepository        $repository,
        PaginatorInterface            $paginator,
        private PlayerTeamRepository  $playerTeamRepository,
        private PlayerTeamTransformer $playerTeamTransformer,
    )
    {
        parent::__construct($serializer, $paginator);
    }

    #[Route('/', name: 'api_team_list', methods: ['GET'])]
    public function list(Request $request, TeamOutputTranformer $outputHandler): JsonResponse
    {
        $pagination = $this->paginator->paginate(
            $this->repository->findAllQB(),
            (int)$request->get('page', 1)
        );
        $data = $this->handleListData($outputHandler, $pagination);

        return $this->json($data, Response::HTTP_OK);
    }

    #[Route('/light/list', name: 'api_team_light_list', methods: ['GET'])]
    public function lightList(): JsonResponse
    {
        return $this->json($this->repository->findAllLight(), Response::HTTP_OK);
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

        return $this->handleInput($validator, $input, $handler, $outputHandler, Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'api_team_update', methods: ['PUT'])]
    public function updateTeam(
        Request              $request,
        string               $id,
        TeamInputHandler     $handler,
        ValidatorInterface   $validator,
        TeamOutputTranformer $outputHandler
    ): Response
    {
        /** @var TeamInput $input */
        $input = $this->serializer->deserialize($request->getContent(), TeamInput::class, 'json');
        $input->teamId = $id;


        return $this->handleInput($validator, $input, $handler, $outputHandler, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'api_team_delete', methods: ['DELETE'])]
    public function removeTeam(Team $team)
    {
        $this->repository->remove($team, true);
        $response = new Response();
        $response->setStatusCode(Response::HTTP_NO_CONTENT);

        return $response;
    }


    #[Route('/{id}/players', name: 'api_team_add_player', methods: ['POST'])]
    public function addPlayer(
        Request                $request,
        string                 $id,
        ValidatorInterface     $validator,
        PlayerTeamInputHandler $playerTeamInputHandler,
        PlayerTeamTransformer  $playerTeamTransformer,
    ): JsonResponse
    {
        /** @var PlayerTeamInput $input */
        $input = $this->serializer->deserialize($request->getContent(), PlayerTeamInput::class, 'json');
        $input->teamId = $id;

        return $this->handleInput($validator, $input, $playerTeamInputHandler, $playerTeamTransformer);
    }

    #[Route('/{id}/players/{playerId}', name: 'api_team_edit_player', methods: ['PUT'])]
    public function editPlayer(
        Request                $request,
        string                 $id,
        string                 $playerId,
        ValidatorInterface     $validator,
        PlayerTeamInputHandler $playerTeamInputHandler,
        PlayerTeamTransformer  $playerTeamTransformer,
    ): JsonResponse
    {
        /** @var PlayerTeamInput $input */
        $input = $this->serializer->deserialize($request->getContent(), PlayerTeamInput::class, 'json');
        $input->teamId = $id;
        $input->playerTeamId = $playerId;

        return $this->handleInput($validator, $input, $playerTeamInputHandler, $playerTeamTransformer);
    }

    #[Route('/{id}/players/{playerId}', name: 'api_team_ell_player', methods: ['PATCH'])]
    public function sellPlayer(Request $request, Team $team)
    {

    }
}