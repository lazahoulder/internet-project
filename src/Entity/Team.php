<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
class Team implements TeamInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['team_list', 'player_team_show', 'bids_list'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['team_list', 'player_team_show', 'bids_list'])]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['team_list'])]
    private ?float $acountBalance = null;

    #[ORM\Column(length: 255)]
    #[Groups(['team_list'])]
    private ?string $country = null;

    #[ORM\OneToMany(mappedBy: 'team', targetEntity: PlayerTeam::class, orphanRemoval: true)]
    private Collection $playerTeams;

    #[ORM\OneToMany(mappedBy: 'team', targetEntity: Bid::class, orphanRemoval: true)]
    private Collection $bids;

    public function __construct()
    {
        $this->playerTeams = new ArrayCollection();
        $this->bids = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAcountBalance(): ?float
    {
        return $this->acountBalance;
    }

    public function setAcountBalance(?float $acountBalance): self
    {
        $this->acountBalance = $acountBalance;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Collection<int, PlayerTeam>
     */
    public function getPlayerTeams(): Collection
    {
        return $this->playerTeams;
    }

    public function addPlayerTeam(PlayerTeamInterface $playerTeam): self
    {
        if (!$this->playerTeams->contains($playerTeam)) {
            $this->playerTeams->add($playerTeam);
            $playerTeam->setTeam($this);
        }

        return $this;
    }

    public function removePlayerTeam(PlayerTeamInterface $playerTeam): self
    {
        if ($this->playerTeams->removeElement($playerTeam)) {
            // set the owning side to null (unless already changed)
            if ($playerTeam->getTeam() === $this) {
                $playerTeam->setTeam(null);
            }
        }

        return $this;
    }

    public function getActivePlayers(): array
    {
        $players = [];

        foreach ($this->getPlayerTeams() as $playerTeam) {
            if (PlayerTeamInterface::INACTIVE_STATE === $playerTeam->getState()) continue;
            $players[] = $playerTeam;
        }

        return $players;
    }

    #[Groups(['team_list'])]
    public function getCountActivePlayers(): int
    {
        return count($this->getActivePlayers());
    }

    public function getInMarketPlayers()
    {
        return $this->getTeamPlayersByStatus(PlayerTeamInterface::IN_MARKET_STATE);
    }

    #[Groups(['team_list'])]
    public function getCountInMarketPlayers(): int
    {
        return count($this->getInMarketPlayers());
    }

    public function getTeamPlayersByStatus(string $status): array
    {
        $players = [];

        foreach ($this->getPlayerTeams() as $playerTeam) {
            if ($status === $playerTeam->getState()) {
                $players[] = $playerTeam->getPlayer();
            }
        }

        return $players;
    }

    public function getBids(): Collection
    {
        return $this->bids;
    }

    public function addBid(Bid $bid): self
    {
        if (!$this->bids->contains($bid)) {
            $this->bids->add($bid);
            $bid->setTeam($this);
        }

        return $this;
    }

    public function removeBid(Bid $bid): self
    {
        if ($this->bids->removeElement($bid)) {
            // set the owning side to null (unless already changed)
            if ($bid->getTeam() === $this) {
                $bid->setTeam(null);
            }
        }

        return $this;
    }

    public function increaseBalance(float $amount): TeamInterface
    {
        $this->acountBalance += $amount;

        return $this;
    }

    public function decreaseBalance(float $amount): TeamInterface
    {
        $this->acountBalance -= $amount;

        return $this;
    }
}
