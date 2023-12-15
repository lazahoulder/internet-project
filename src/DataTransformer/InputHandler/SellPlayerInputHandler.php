<?php

namespace App\DataTransformer\InputHandler;

use App\Dto\IntputDTO\SellPlayer;
use App\Repository\PlayerTeamRepository;
use App\Service\PlayerService;
use Doctrine\ORM\EntityManagerInterface;

class SellPlayerInputHandler implements InputHandlerInterface
{
    public function __construct(
        private PlayerService $playerService,
        private PlayerTeamRepository $playerTeamRepository,
    )
    {
    }

    /**
     * @param SellPlayer $data
     * @return mixed
     */
    public function handle($data): mixed
    {
        $playerTeam = $this->playerTeamRepository->find($data->playerTeamId);

        if (is_null($playerTeam)) {
            throw new Exception(sprintf('Player with id %s not found', $data->playerTeamId));
        }

        $this->playerService->sellPlayer($playerTeam, (int)$data->sellValue);

        return $playerTeam;
    }
}