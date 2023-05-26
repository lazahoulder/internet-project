<?php

namespace App\DataTransformer\OutputHandlar;

use App\Entity\PlayerTeamInterface;
use App\Entity\Team;
use App\Entity\TeamInterface;
use App\Repository\PlayerTeamRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class TeamOutputTranformer
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

    public function normalize(Team $team)
    {
        return $this->objectNormalizer->normalize($team, null, ['groups' => 'team_show']);
    }
}