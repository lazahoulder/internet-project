<?php

namespace App\Dto\IntputDTO;

use Symfony\Component\Validator\Constraints as Assert;
class HirePlayerInput
{
    public mixed $playerId;
    #[Assert\NotBlank]
    public null|string|int $teamId;
    public ?\DateTimeInterface $exceptedEndDate;
    public ?float $value;
}