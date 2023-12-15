<?php

namespace App\Dto\IntputDTO;

use Symfony\Component\Validator\Constraints as Assert;
class SellPlayer
{
    public mixed $playerTeamId;
    #[Assert\NotBlank]
    #[Assert\PositiveOrZero]
    #[Assert\NotEqualTo(
        value: 0,
    )]
    public ?float $sellValue;
}