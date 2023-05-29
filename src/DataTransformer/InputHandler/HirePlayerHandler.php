<?php

namespace App\DataTransformer\InputHandler;

use App\Dto\IntputDTO\HirePlayerInput;
use App\Entity\PlayerTeamInterface;
use App\Repository\PlayerRepository;
use App\Repository\TeamRepository;
use App\Service\PlayerService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class HirePlayerHandler implements InputHandlerInterface
{
    public function __construct(
        private PlayerRepository $playerRepository,
        private TeamRepository $teamRepository,
        private PlayerService $playerService
    )
    {}

    /**
     * @param HirePlayerInput $data
     * @return PlayerTeamInterface
     * @throws Exception
     */
    public function handle($data): PlayerTeamInterface
    {
        $player = $this->playerRepository->find($data->playerId);

        if (is_null($player)) {
            throw new Exception(sprintf('Player with id %s not found', $data->playerId));
        }

        $team = $this->teamRepository->find($data->teamId);

        if (is_null($team)) {
            throw new Exception(sprintf('Team with id %s not found', $data->teamId));
        }

        return $this->playerService->hirePlayer(
            $player,
            $team,
            $data->exceptedEndDate,
            $data->value
        );
    }
}