<?php

namespace App\DataTransformer\OutputHandlar;

use App\Entity\PlayerTeamInterface;
use App\Entity\TeamInterface;
use App\Repository\PlayerTeamRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class TeamOutputHandler
{
    public function __construct(
        private ObjectNormalizer $objectNormalizer,
    )
    {}

    public function listNormalize(iterable $teams): array
    {
        $data = [];

        foreach ($teams as $team) {
            $data[] = $this->objectNormalizer->normalize($team, null, ['groups' => 'team_list']);
        }

        return $data;
    }

    public function simpleTransfo(PlayerTeamInterface $playerTeam)
    {

    }
}