<?php

namespace App\DataTransformer\InputHandler;

use App\Dto\IntputDTO\PlayerInput;
use App\Dto\IntputDTO\PlayerTeamInput;
use App\Entity\Player;
use App\Entity\PlayerTeam;
use App\Entity\PlayerTeamInterface;
use App\Repository\PlayerRepository;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class PlayerCreationHandler implements InputHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TeamRepository $teamRepository,
        private PlayerRepository $playerRepository,
    )
    {
    }

    /**
     * @param PlayerInput $data
     * @return Player
     * @throws Exception
     */
    public function handle($data): Player
    {
        $player = null;

        if ($data->playerId) {
            $player = $this->playerRepository->find($data->playerId);

            if (is_null($player)) {
                throw new Exception(sprintf('Player with id %s not found', $data->playerId));
            }
        }

        $player = $player ?? new Player();
        $player->setName($data->name);
        $player->setSurname($data->surname);
        $this->entityManager->persist($player);
        $this->entityManager->flush();

        if ($data->teamId) {
            $team = $this->teamRepository->find($data->teamId);

            if (is_null($team)) {
                throw new Exception(sprintf('Team with id %s not found', $data->teamId));
            }

            $playerTeam = new PlayerTeam();

            $playerTeam
                ->setTeam($team)
                ->setState(PlayerTeamInterface::ACTIVE_STATE)
                ->setStartDate(new \DateTime())
                ->setPlayer($player);

            $this->entityManager->persist($playerTeam);
            $this->entityManager->flush();

        }

        return  $player;
    }
}