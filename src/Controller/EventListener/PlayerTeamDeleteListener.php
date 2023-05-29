<?php

namespace App\Controller\EventListener;

use App\Entity\PlayerTeam;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PreRemoveEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\PreRemove;

#[AsEntityListener(event: Events::preRemove, method: 'preRemove', entity: PlayerTeam::class)]
class PlayerTeamDeleteListener
{
    public function __construct(
        private EntityManagerInterface $manager,
    ){}

    public function preRemove(PlayerTeam $playerTeam, PreRemoveEventArgs $eventArgs)
    {
        foreach ($playerTeam->getBids() as $bid) {
            $this->manager->remove($bid);
        }

        $this->manager->flush();
    }
}