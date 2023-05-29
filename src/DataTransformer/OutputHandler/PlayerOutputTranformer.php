<?php

namespace App\DataTransformer\OutputHandler;

use App\Entity\Player;
use App\Entity\PlayerInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class PlayerOutputTranformer implements OutputTransformerInterface
{
    public function __construct(
        private ObjectNormalizer $objectNormalizer,
    )
    {
    }

    /**
     * @param iterable|PlayerInterface[] $objects
     * @return array
     * @throws ExceptionInterface
     */
    public function listNormalize(iterable $objects): array
    {
        $data = [];
        foreach ($objects as $object) {
            $data[] = $this->normalize($object);
        }

        return $data;
    }

    /**
     * @param PlayerInterface $object
     * @return array
     * @throws ExceptionInterface
     */
    public function normalize($object): array
    {
        $data = $this->objectNormalizer->normalize($object, null, ['groups' => 'player_show']);
        $data['teamId'] = $object->getActualTeam()?->getId();

        return $data;
    }
}