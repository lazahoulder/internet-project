<?php

namespace App\DataFixtures;

use App\Entity\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TeamFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $teams = [
            [
                'name' => 'usca foot',
                'country' => 'Madagascar',
                'acountBalance' => 100000
            ],
            [
                'name' => 'Tana foot',
                'country' => 'Madagascar',
                'acountBalance' => 100000
            ],
            [
                'name' => 'cosfa foot',
                'country' => 'Madagascar',
                'acountBalance' => 100000
            ],
            [
                'name' => 'cospn foot',
                'country' => 'Madagascar',
                'acountBalance' => 100000
            ],
            [
                'name' => 'real madrid',
                'country' => 'Spain',
                'acountBalance' => 100000
            ],
            [
                'name' => 'PSG',
                'country' => 'France',
                'acountBalance' => 100000
            ],
            [
                'name' => '67 city',
                'country' => 'France',
                'acountBalance' => 100000
            ],
            [
                'name' => 'Nantes FC',
                'country' => 'France',
                'acountBalance' => 100000
            ],

            [
                'name' => 'Strasbourg FC',
                'country' => 'France',
                'acountBalance' => 100000
            ],
            [
                'name' => 'los angeles angel',
                'country' => 'USA',
                'acountBalance' => 100000
            ],
        ];

        foreach ($teams as $item) {
            $team = new Team();
            $team
                ->setName($item['name'])
                ->setCountry($item['country'])
                ->setAcountBalance($item['acountBalance']);
            $manager->persist($team);
        }

        $manager->flush();
    }
}
