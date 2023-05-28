<?php

namespace App\DataTransformer\InputHandler;

use App\Dto\IntputDTO\PlayerTeamInput;
use App\Dto\IntputDTO\TeamInput;
use App\Entity\Player;
use App\Entity\PlayerTeam;
use App\Entity\PlayerTeamInterface;
use App\Entity\Team;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TeamInputHandler implements InputHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $manager,
        private TeamRepository $repository,
    )
    {
    }

    /**
     * @param TeamInput $data
     * @return mixed|Team
     * @throws Exception
     */
    public function handle($data): mixed
    {
        $team = null;
        if ($data->teamId) {
            $team = $this->repository->find($data->teamId);

            if (is_null($team)) {
                throw new Exception(sprintf('Team with id %s not found', $data->teamId));
            }
        }
        $team = $team ?? new Team();
        $team
            ->setName($data->name)
            ->setCountry($data->country)
            ->setAcountBalance($data->acountBalance);

        $this->manager->persist($team);

        foreach ($data->players as $playerInput) {
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