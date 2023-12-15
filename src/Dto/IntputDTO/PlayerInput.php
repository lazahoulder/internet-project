<?php

namespace App\Dto\IntputDTO;

use Symfony\Component\Validator\Constraints as Assert;
class PlayerInput
{
    #[Assert\NotBlank]
    public string $name;
    #[Assert\NotBlank]
    public string $surname;
    public ?string $teamId = null;
    public mixed $playerId = null;
}