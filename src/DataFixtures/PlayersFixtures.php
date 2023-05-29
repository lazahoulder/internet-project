<?php

namespace App\DataFixtures;

use App\Entity\Player;
use App\Entity\PlayerTeam;
use App\Entity\PlayerTeamInterface;
use App\Entity\Team;
use App\Repository\TeamRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PlayersFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $playersData = [
            [
                'name' => 'Laza',
                'surname' => 'BE'
            ],
            [
                'name' => 'Toto',
                'surname' => 'BE'
            ],
            [
                'name' => 'Kaka',
                'surname' => 'BE'
            ],
            [
                'name' => 'Zoky',
                'surname' => 'BE'
            ],
            [
                'name' => 'Zefa',
                'surname' => 'BE'
            ],
        ];

        $team = new Team();
        $team
            ->setName('man city')
            ->setCountry('england')
            ->setAcountBalance(100000);
        $manager->persist($team);

        foreach ($playersData as $datum) {
            $player = new Player();
            $player
                ->setName($datum['name'])
                ->setSurname($datum['surname'])
            ;

            $manager->persist($player);

            $playerTeam = new PlayerTeam();
            $playerTeam
                ->setTeam($team)
                ->setPlayer($player)
                ->setState(PlayerTeamInterface::ACTIVE_STATE)
                ->setAmountValue(20000)
                ->setStartDate(new \DateTime());

            $manager->persist($playerTeam);
        }

        $manager->flush();
    }
}
