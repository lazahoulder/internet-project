<?php

namespace App\Dto\IntputDTO;

use DateTimeInterface;
use Symfony\Component\Validator\Constraints as Assert;

class PlayerTeamInput
{
    #[Assert\NotBlank]
    public string $name;
    #[Assert\NotBlank]
    public string $surname;
    public ?float $value;
    public ?DateTimeInterface $expectedEndDate;
    public ?string $teamId = null;
    public ?string $playerTeamId = null;
}