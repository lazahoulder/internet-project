<?php

namespace App\DataTransformer\InputHandler;

use App\Dto\IntputDTO\PlayerTeamInput;
use App\Entity\Player;
use App\Entity\PlayerTeam;
use App\Entity\PlayerTeamInterface;
use App\Entity\Team;
use App\Repository\PlayerTeamRepository;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class PlayerTeamInputHandler implements InputHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $manager,
        private TeamRepository         $teamRepository,
        private PlayerTeamRepository   $playerTeamRepository,
    )
    {
    }

    /**
     * @param PlayerTeamInput $data
     * @return PlayerTeamInterface
     * @throws Exception
     */
    public function handle($data) : PlayerTeamInterface
    {
        $team = $this->teamRepository->find($data->teamId);

        if (is_null($team)) {
            throw new Exception(sprintf('Team with id %s not found', $data->teamId));
        }

        $playerTeam = null;

        if ($data->playerTeamId) {
            $playerTeam = $this->playerTeamRepository->find($data->playerTeamId);
            if (is_null($playerTeam)) {
                throw new Exception(sprintf('Player with id %s not found', $data->playerTeamId));
            }
        }

        $playerTeam = $playerTeam ?? new PlayerTeam();

        //create or update player
        $player = $playerTeam->getPlayer() ?? new Player();
        $player->setName($data->name);
        $player->setSurname($data->surname);

        $this->manager->persist($player);
        $this->manager->flush();

        // set playerTeam data
        $playerTeam->setTeam($team);
        $playerTeam->setExpectedEndDate($data->expectedEndDate);
        if (is_null($playerTeam->getState())) {
            $playerTeam->setState(PlayerTeamInterface::ACTIVE_STATE);
        }

        if (is_null($playerTeam->getStartDate())) {
            $playerTeam->setStartDate(new \DateTime());
        }

        $playerTeam->setAmountValue((float)$data->value);
        $playerTeam->setPlayer($player);

        $this->manager->persist($playerTeam);
        $this->manager->flush();

        return $playerTeam;
    }
}