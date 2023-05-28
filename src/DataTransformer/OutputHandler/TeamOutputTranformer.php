<?php

namespace App\DataTransformer\OutputHandler;

use App\Entity\PlayerTeamInterface;
use App\Entity\Team;
use App\Entity\TeamInterface;
use App\Repository\PlayerTeamRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class TeamOutputTranformer implements OutputTransformerInterface
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

    /**
     * @param $team
     * @return array|\ArrayObject|bool|float|int|mixed|string|null
     * @throws ExceptionInterface
     */
    public function normalize($team)
    {
        return $this->objectNormalizer->normalize($team, null, ['groups' => 'team_list']);
    }
}