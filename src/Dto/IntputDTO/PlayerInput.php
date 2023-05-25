<?php

namespace App\Dto\IntputDTO;

use DateTimeInterface;
use Symfony\Component\Validator\Constraints as Assert;

class PlayerInput
{
    #[Assert\NotBlank]
    public string $name;
    #[Assert\NotBlank]
    public string $surname;
    public ?string $value;
    public ?DateTimeInterface $expectedEndDate;
}