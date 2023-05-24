<?php

namespace App\DataTransformer\InputHandler;

use App\Dto\IntputDTO\TeamInput;
use App\Entity\Team;
use App\Repository\TeamRepository;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TeamInputHandler
{
    public function __construct(
        private TeamRepository $repository,
    )
    {

    }

    public function handle(TeamInput $teamInput, ?Team $team = null): Team|ConstraintViolationListInterface
    {
        $team = $team ?? new Team();
        $team
            ->setName($teamInput->name)
            ->setCountry($teamInput->country)
            ->setAcountBalance($teamInput->acountBalance)
        ;

        $this->repository->save($team, true);

        return $team;
    }
}