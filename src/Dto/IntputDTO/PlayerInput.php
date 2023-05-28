<?php

namespace App\Dto\IntputDTO;

class PlayerInput
{
    #[Assert\NotBlank]
    public string $name;
    #[Assert\NotBlank]
    public string $surname;
    public ?string $teamId = null;
    public ?string $playerId = null;
}