<?php

namespace App\Entity;

use App\Repository\PlayerTeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlayerTeamRepository::class)]
class PlayerTeam implements PlayerTeamInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(nullable: true)]
    private ?float $amountValue = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column(length: 255)]
    private ?string $state = null;

    #[ORM\ManyToOne(inversedBy: 'playerTeams')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Player $player = null;

    #[ORM\ManyToOne(inversedBy: 'playerTeams')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Team $team = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $expectedEndDate = null;

    #[ORM\Column(nullable: true)]
    private ?float $sellingValue = null;

    #[ORM\OneToMany(mappedBy: 'playerTeam', targetEntity: Bid::class, orphanRemoval: true)]
    private Collection $bids;

    public function __construct()
    {
        $this->bids = new ArrayCollection();
        $this->state = PlayerTeamInterface::ACTIVE_STATE;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getAmountValue(): ?float
    {
        return $this->amountValue;
    }

    public function setAmountValue(?float $amountValue): self
    {
        $this->amountValue = $amountValue;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): self
    {
        $this->player = $player;

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

    public function getExpectedEndDate(): ?\DateTimeInterface
    {
        return $this->expectedEndDate;
    }

    public function setExpectedEndDate(?\DateTimeInterface $expectedEndDate): self
    {
        $this->expectedEndDate = $expectedEndDate;

        return $this;
    }

    public function getSellingValue(): ?float
    {
        return $this->sellingValue;
    }

    public function setSellingValue(?float $sellingValue): self
    {
        $this->sellingValue = $sellingValue;

        return $this;
    }

    public function publishInMarketPlayer(float $sellValue): PlayerTeamInterface
    {
        $this
            ->setSellingValue($sellValue)
            ->setState(PlayerTeamInterface::IN_MARKET_STATE);

        return $this;
    }

    public function closePlayerContract(): void
    {
        $this->setEndDate(new \DateTime())
        ->setState(PlayerTeamInterface::INACTIVE_STATE);
    }

    /**
     * @return Collection<int, Bid>
     */
    public function getBids(): Collection
    {
        return $this->bids;
    }

    public function addBid(Bid $bid): self
    {
        if (!$this->bids->contains($bid)) {
            $this->bids->add($bid);
            $bid->setPlayerTeam($this);
        }

        return $this;
    }

    public function removeBid(Bid $bid): self
    {
        if ($this->bids->removeElement($bid)) {
            // set the owning side to null (unless already changed)
            if ($bid->getPlayerTeam() === $this) {
                $bid->setPlayerTeam(null);
            }
        }

        return $this;
    }
}
