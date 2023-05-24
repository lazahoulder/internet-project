<?php

namespace App\Entity;

use App\Repository\PlayerTeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ReadableCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ORM\Entity(repositoryClass: PlayerTeamRepository::class)]
class PlayerTeam implements PlayerTeamInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['team_show', 'player_team_show'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['team_show', 'player_team_show'])]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['team_show', 'player_team_show'])]
    private ?float $amountValue = null;

    #[ORM\Column(length: 255)]
    #[Groups(['team_show', 'player_team_show'])]
    private ?string $state = null;

    #[ORM\ManyToOne(inversedBy: 'playerTeams')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['team_show', 'player_team_show'])]
    private ?Player $player = null;

    #[ORM\ManyToOne(inversedBy: 'playerTeams')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['player_team_show'])]
    private ?Team $team = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups(['team_show', 'player_team_show'])]
    private ?\DateTimeInterface $expectedEndDate = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['team_show', 'player_team_show'])]
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

    #[Groups(['team_show', 'player_team_show'])]
    public function getCountActiveBid() : int
    {
        return $this->getActiveBids()->count();
    }

    public function getActiveBids() : ReadableCollection
    {
        return $this->getBids()->filter(function($element) {
            return $element > 1;
        });
    }
}
