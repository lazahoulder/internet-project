<?php

namespace App\DataTransformer\OutputHandler;

use App\Entity\Team;

interface OutputTransformerInterface
{
    public function listNormalize(iterable $objects): array;

    /**
     * @param mixed $object
     * @return mixed
     */
    public function normalize($object);
}