<?php

namespace App\Dto\IntputDTO;

use Symfony\Component\Validator\Constraints as Assert;
class TeamInput
{
    public ?string $teamId;
    #[Assert\NotBlank]
    public string $name;
    #[Assert\NotBlank]
    public string $country;
    #[Assert\NotBlank]
    public float $acountBalance;

    /**
     * @var PlayerTeamInput[]
     */
    #[Assert\Valid]
    public array $players;
}