<?php

namespace App\Service;

use App\Entity\TeamInterface;
use App\Repository\PlayerRepository;
use App\Repository\PlayerTeamRepository;
use App\Repository\TeamRepository;

class TeamService
{
    public function __construct(
        private TeamRepository $teamRepository,
        private PlayerTeamRepository $playerTeamRepository,
        private PlayerRepository $playerRepository,
    )
    {

    }
}