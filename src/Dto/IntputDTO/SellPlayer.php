<?php

namespace App\Dto\IntputDTO;

class SellPlayer
{
    public mixed $playerTeamId;
    #[Assert\NotBlank]
    public ?float $sellValue;
}