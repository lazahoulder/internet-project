<?php

namespace App\Dto\IntputDTO;

use Symfony\Component\Validator\Constraints as Assert;
class BidInput
{
    public mixed $playerTeamId;
    #[Assert\NotBlank]
    #[Assert\PositiveOrZero]
    #[Assert\NotEqualTo(
        value: 0,
    )]
    public mixed $value;
    #[Assert\NotBlank]
    public mixed $teamId;
}