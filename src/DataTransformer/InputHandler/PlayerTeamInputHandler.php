<?php

namespace App\DataTransformer\InputHandler;

use App\Dto\IntputDTO\PlayerInput;
use App\Entity\Player;
use App\Entity\PlayerTeam;
use App\Entity\PlayerTeamInterface;
use App\Entity\Team;
use App\Repository\PlayerTeamRepository;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;

class PlayerTeamInputHandler
{
    public function __construct(
        private EntityManagerInterface $manager,
        private TeamRepository         $teamRepository,
        private PlayerTeamRepository   $playerTeamRepository,
    )
    {
    }

    public function handle(PlayerInput $playerInput) : PlayerTeamInterface
    {
        $team = $this->teamRepository->find($playerInput->teamId);

        if (is_null($team)) {
            throw new \Exception(sprintf('Team with id %s not found', $playerInput->teamId));
        }

        $playerTeam = null;

        if ($playerInput->playerTeamId) {
            $playerTeam = $this->playerTeamRepository->find($playerInput->playerTeamId);
            if (is_null($playerTeam)) {
                throw new \Exception(sprintf('Player with id %s not found', $playerInput->playerTeamId));
            }
        }

        $playerTeam = $playerTeam ?? new PlayerTeam();

        //create or update player
        $player = $playerTeam->getPlayer() ?? new Player();
        $player->setName($playerInput->name);
        $player->setSurname($playerInput->surname);

        $this->manager->persist($player);
        $this->manager->flush();

        // set playerTeam data
        $playerTeam->setTeam($team);
        $playerTeam->setExpectedEndDate($playerInput->expectedEndDate);
        if (is_null($playerTeam->getState())) {
            $playerTeam->setState(PlayerTeamInterface::ACTIVE_STATE);
        }

        if (is_null($playerTeam->getStartDate())) {
            $playerTeam->setStartDate(new \DateTime());
        }

        $playerTeam->setAmountValue((float)$playerInput->value);
        $playerTeam->setPlayer($player);

        $this->manager->persist($playerTeam);
        $this->manager->flush();

        return $playerTeam;
    }
}