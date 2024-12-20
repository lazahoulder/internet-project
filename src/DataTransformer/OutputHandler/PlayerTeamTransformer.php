<?php

namespace App\DataTransformer\OutputHandler;

use App\Entity\PlayerTeam;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class PlayerTeamTransformer implements OutputTransformerInterface
{
    public function __construct(
        private ObjectNormalizer $objectNormalizer,
    )
    {}

    public function listNormalize(iterable $playerTeams): array
    {
        $data = [];

        foreach ($playerTeams as $playerTeam) {
            $data[] = $this->objectNormalizer->normalize($playerTeam, null, ['groups' => 'player_team_show']);
        }

        return $data;
    }

    public function normalize($playerTeam)
    {
        return $this->objectNormalizer->normalize($playerTeam, null, ['groups' => 'player_team_show']);
    }
}