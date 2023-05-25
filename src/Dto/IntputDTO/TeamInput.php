<?php

namespace App\Dto\IntputDTO;

use Symfony\Component\Validator\Constraints as Assert;
class TeamInput
{
    #[Assert\NotBlank]
    public string $name;
    #[Assert\NotBlank]
    public string $country;
    #[Assert\NotBlank]
    public float $acountBalance;
    /**
     * @var PlayerInput[]
     */
    public array $players;
}