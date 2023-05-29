<?php

namespace App\Entity;

use App\Repository\BidRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BidRepository::class)]
class Bid implements BidInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['bids_list'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['bids_list'])]
    private ?float $value = null;

    #[ORM\ManyToOne(inversedBy: 'bids')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['bids_list'])]
    private ?PlayerTeam $playerTeam = null;

    #[ORM\ManyToOne(inversedBy: 'bids')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['bids_list'])]
    private ?Team $team = null;

    #[ORM\Column(nullable: true)]
    private ?bool $closed = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['bids_list'])]
    private ?\DateTimeInterface $date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getPlayerTeam(): ?PlayerTeam
    {
        return $this->playerTeam;
    }

    public function setPlayerTeam(?PlayerTeam $playerTeam): self
    {
        $this->playerTeam = $playerTeam;

        return $this;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): self
    {
        $this->team = $team;

        return $this;
    }

    public function isClosed(): ?bool
    {
        return $this->closed;
    }

    public function setClosed(?bool $closed): self
    {
        $this->closed = $closed;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
