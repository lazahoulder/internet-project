<?php

namespace App\DataTransformer\InputHandler;

use App\Dto\IntputDTO\PlayerInput;
use App\Dto\IntputDTO\TeamInput;
use App\Entity\Player;
use App\Entity\PlayerTeam;
use App\Entity\PlayerTeamInterface;
use App\Entity\Team;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TeamInputHandler
{
    public function __construct(
        private EntityManagerInterface $manager,
    )
    {

    }

    public function handle(TeamInput $teamInput, ?Team $team = null): Team|ConstraintViolationListInterface
    {
        $team = $team ?? new Team();
        $team
            ->setName($teamInput->name)
            ->setCountry($teamInput->country)
            ->setAcountBalance($teamInput->acountBalance);

        $this->manager->persist($team);


        //dd($teamInput->players);

        /** @var PlayerInput $playerInput */
        foreach ($teamInput->players as $playerInput) {
            $player = new Player();
            $player->setName($playerInput->name);
            $player->setSurname($playerInput->surname);
            $this->manager->persist($player);
            $playerTeam = new PlayerTeam();
            $playerTeam->setStartDate(new \DateTime());
            $playerTeam->setTeam($team);
            $playerTeam->setPlayer($player);
            $playerTeam->setAmountValue((int)$playerInput->value);
            $playerTeam->setState(PlayerTeamInterface::ACTIVE_STATE);
            $this->manager->persist($playerTeam);

        }

        $this->manager->flush();

        return $team;
    }
}