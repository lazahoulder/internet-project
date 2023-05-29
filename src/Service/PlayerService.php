<?php

namespace App\Service;

use App\Entity\PlayerInterface;
use App\Entity\PlayerTeam;
use App\Entity\PlayerTeamInterface;
use App\Entity\TeamInterface;
use App\Repository\PlayerRepository;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;

class PlayerService
{
    public function __construct(
        private EntityManagerInterface $manager,
        private PlayerRepository $playerRepository,
    )
    {

    }

    public function sellPlayer(PlayerTeamInterface $playerTeam, float $value)
    {
        $playerTeam->publishInMarketPlayer($value);
        $this->manager->flush();
    }

    /**
     * @param PlayerInterface $player
     * @param TeamInterface $team
     * @param int|null $value
     * @return PlayerTeam
     */
    public function hirePlayer(
        PlayerInterface $player,
        TeamInterface $team,
        ?DateTimeInterface $expectedEnddate = null,
        ?int $value = null,
    ): PlayerTeam
    {
        $playerTeam = new PlayerTeam();
        $playerTeam
            ->setPlayer($player)
            ->setTeam($team)
            ->setStartDate(new \DateTime())
            ->setState(PlayerTeamInterface::ACTIVE_STATE)
            ->setExpectedEndDate($expectedEnddate)
            ->setAmountValue($value)
        ;
        $this->manager->persist($playerTeam);
        $this->manager->flush();

        return $playerTeam;
    }

    public function freePlayer(PlayerTeamInterface $playerTeam): void
    {
        $playerTeam->closePlayerContract();
        $this->manager->flush();
    }

    public function getInactivePlayers(int $page = 1, int $limit = 10): array
    {
        $inactivQuery = $this->playerRepository->findInactivePlayersQuery();
        $total = $this->playerRepository->countInactivePlayers($inactivQuery);
        $data = $this->playerRepository->getPaginateNativeData($inactivQuery, $page, $limit);
        $pageCount = (int)ceil($total/$limit);

        return [
            'results' => $data,
            'page' => $page,
            'pageNumber' => $pageCount,
            'total' => $total
        ];
    }
}