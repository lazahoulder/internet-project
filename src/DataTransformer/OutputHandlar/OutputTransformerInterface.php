<?php

namespace App\DataTransformer\OutputHandlar;

use App\Entity\Team;

interface OutputTransformerInterface
{
    public function listNormalize(iterable $teams): array;

    /**
     * @param mixed $object
     * @return mixed
     */
    public function normalize($object);
}