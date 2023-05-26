<?php

namespace App\DataTransformer\OutputHandlar;

use App\Entity\PlayerTeam;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class PlayerTeamTransformer
{
    public function __construct(
        private ObjectNormalizer $objectNormalizer,
    )
    {}

    public function listNormalize(iterable $playerTeams): array
    {
        $data = [];

        foreach ($playerTeams as $playerTeam) {
            $data[] = $this->objectNormalizer->normalize($playerTeam, null, ['groups' => 'team_show']);
        }

        return $data;
    }

    public function normalize(PlayerTeam $playerTeam)
    {
        return $this->objectNormalizer->normalize($playerTeam, null, ['groups' => 'team_show']);
    }
}