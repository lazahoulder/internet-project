<?php

namespace App\DataTransformer\OutputHandlar;

use App\Entity\PlayerTeamInterface;
use App\Entity\TeamInterface;
use App\Repository\PlayerTeamRepository;

class TeamHandler
{
    public function __construct(
        private PlayerTeamRepository $playerTeamRepository,
    )
    {}

    public function transformOne(TeamInterface $team)
    {

    }

    public function simpleTransfo(PlayerTeamInterface $playerTeam)
    {

    }
}