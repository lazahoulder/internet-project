<?php

namespace App\DataTransformer\OutputHandler;

use App\Entity\BidInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class BidsOutputTransformer implements OutputTransformerInterface
{
    public function __construct(
        private ObjectNormalizer $objectNormalizer,
    )
    {}

    /**
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
     * @param BidInterface $object
     * @return mixed
     * @throws ExceptionInterface
     */
    public function normalize($object): mixed
    {
        $player = $object->getPlayerTeam()->getPlayer();
        $data = $this->objectNormalizer->normalize($object, null, ['groups' => 'bids_list']);
        $data['player'] = $player->getName() . ' ' . $player->getSurname();
        $data['team'] = $object->getTeam()->getName();

        return $data;
    }
}