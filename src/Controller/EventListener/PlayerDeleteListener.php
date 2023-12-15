<?php

namespace App\Controller\EventListener;

use App\Entity\Player;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PreRemoveEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::preRemove, method: 'preRemove', entity: Player::class)]
class PlayerDeleteListener
{
    public function __construct(
        private EntityManagerInterface $manager,
    ){}

    public function preRemove(Player $player, PreRemoveEventArgs $eventArgs): void
    {
        foreach ($player->getPlayerTeams() as $playerTeam) {
            $this->manager->remove($playerTeam);
        }

        $this->manager->flush();
    }
}