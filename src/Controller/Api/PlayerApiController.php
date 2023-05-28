<?php

namespace App\Controller\Api;

use App\DataTransformer\InputHandler\SellPlayerInputHandler;
use App\DataTransformer\OutputHandler\PlayerTeamTransformer;
use App\Dto\IntputDTO\PlayerTeamInput;
use App\Dto\IntputDTO\SellPlayer;
use App\Entity\Player;
use App\Entity\PlayerTeam;
use App\Repository\PlayerRepository;
use App\Repository\PlayerTeamRepository;
use App\Service\PlayerService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('api/players')]
class PlayerApiController extends BaseApiController
{
    use HasInputHandlerTrait;

    public function __construct(
        SerializerInterface $serializer,
        PaginatorInterface $paginator,
        private PlayerTeamRepository $playerTeamRepository,
        private PlayerRepository $playerRepository,
        private PlayerService $playerService,
        private PlayerTeamTransformer $playerTeamTransformer,
        private ValidatorInterface $validator,
    )
    {
        parent::__construct($serializer, $paginator);
    }

    #[Route('/', name: 'api_players_list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $pagination = $this->paginator->paginate(
            $this->playerTeamRepository->findActivePlayersQb($request->get('teamId')),
            (int)$request->get('page', 1)
        );

        $data = $this->handleListData($this->playerTeamTransformer, $pagination);

        return $this->json($data, Response::HTTP_OK);
    }

    #[Route('/inactive/list', name: 'api_players_list_inactive', methods: ['GET'])]
    public function listInactive(Request $request)
    {

    }

    #[Route('/', name: 'api_players_create', methods: ['POST'])]
    public function add(Request $request)
    {
        /** @var PlayerTeamInput $input */
        $input = $this->serializer->deserialize($request->getContent(), PlayerTeamInput::class, 'json');

    }

    #[Route('/{id}', name: 'api_players_hire', methods: ['PATCH'])]
    public function hire(PlayerTeam $playerTeam)
    {

    }

    #[Route('/{id}', name: 'api_players_sell', methods: ['PUT'])]
    public function sell(
        Request $request,
        string $id,
        SellPlayerInputHandler $inputHandler,
        PlayerTeamTransformer $playerTeamTransformer
    )
    {
        /** @var SellPlayer $input */
        $input = $this->serializer->deserialize($request->getContent(), SellPlayer::class, 'json');
        $input->playerTeamId = $id;

        return $this->handleInput($this->validator, $input, $inputHandler, $playerTeamTransformer, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'api_players_fire', methods: ['DELETE'])]
    public function fire(PlayerTeam $playerTeam): Response
    {
        $this->playerService->freePlayer($playerTeam);
        $response = new Response();
        $response->setStatusCode(Response::HTTP_NO_CONTENT);

        return $response;

    }
}