<?php

namespace App\Service;

use App\Entity\PlayerInterface;
use App\Entity\PlayerTeam;
use App\Entity\TeamInterface;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class PlayerService
{
    public function __construct(
        private EntityManagerInterface $manager,
    ){}

    public function sellPlayer(PlayerInterface $player, float $value)
    {
        $playerTeam = $player->getActualActiveStatus();
        $playerTeam->publishInMarketPlayer($value);
        $this->manager->flush();
    }

    /**
     * @param TeamInterface $buyerTeam
     * @param PlayerInterface $player
     * @param float $value
     * @param DateTimeInterface|null $expectedEndDate
     * @return void
     * @throws Exception
     */
    public function buyPlayer(
        TeamInterface      $buyerTeam,
        PlayerInterface    $player,
        float              $value,
        ?DateTimeInterface $expectedEndDate = null,
    ): void
    {
        if ($buyerTeam->getAcountBalance() < $value) {
            throw new Exception('Team cannot afford player');
        }

        $actualStatus = $player->getActualActiveStatus();
        $actualStatus->closePlayerContract();
        $sellerTeam = $player->getActualTeam();
        $sellerTeam->increaseBalance($value);
        $buyerTeam->decreaseBalance($value);
        $newStatus = new PlayerTeam();
        $newStatus
            ->setTeam($buyerTeam)
            ->setPlayer($player)
            ->setAmountValue($value)
            ->setStartDate(new \DateTime())
            ->setExpectedEndDate($expectedEndDate)
        ;

        $this->manager->persist($newStatus);
        $this->manager->flush();
    }

    public function placeBidPlayer(TeamInterface $team, float $amount)
    {

    }
}