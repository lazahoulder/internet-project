<?php

namespace App\Service;

use App\Entity\BidInterface;
use App\Entity\PlayerTeam;
use App\Entity\PlayerTeamInterface;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class BidService
{
    public function __construct(
        private EntityManagerInterface $manager,
    ){}

    /**
     * @throws Exception
     */
    public function acceptBid(BidInterface $bid)
    {
        $buyerTeam = $bid->getTeam();
        if ($buyerTeam->getAcountBalance() < $bid->getValue()) {
            throw new Exception('Team cannot afford player');
        }

        $actualStatus = $bid->getPlayerTeam();
        $actualStatus->closePlayerContract();
        $sellerTeam = $actualStatus->getTeam();
        $sellerTeam->increaseBalance($bid->getValue());
        $buyerTeam->decreaseBalance($bid->getValue());
        $newStatus = new PlayerTeam();
        $newStatus
            ->setTeam($buyerTeam)
            ->setPlayer($actualStatus->getPlayer())
            ->setAmountValue($bid->getValue())
            ->setStartDate(new \DateTime())
            ->setState(PlayerTeamInterface::ACTIVE_STATE)
        ;
        foreach ($actualStatus->getBids() as $bid) {
            $this->manager->remove($bid);
        }
        $this->manager->persist($newStatus);
        $this->manager->flush();
    }
}