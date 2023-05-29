<?php

namespace App\Entity;

use App\Repository\PlayerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ORM\Entity(repositoryClass: PlayerRepository::class)]
class Player implements PlayerInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['player_show', 'player_team_show', 'bids_list'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['player_show', 'player_team_show', 'bids_list'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['player_show', 'player_team_show', 'bids_list'])]
    private ?string $surname = null;

    #[ORM\OneToMany(mappedBy: 'player', targetEntity: PlayerTeam::class, orphanRemoval: true)]
    private Collection $playerTeams;

    public function __construct()
    {
        $this->playerTeams = new ArrayCollection();
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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

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
            $playerTeam->setPlayer($this);
        }

        return $this;
    }

    public function removePlayerTeam(PlayerTeamInterface $playerTeam): self
    {
        if ($this->playerTeams->removeElement($playerTeam)) {
            // set the owning side to null (unless already changed)
            if ($playerTeam->getPlayer() === $this) {
                $playerTeam->setPlayer(null);
            }
        }

        return $this;
    }

    public function getActualActiveStatus(): ?PlayerTeamInterface
    {
        return $this->getPlayerTeams()->findFirst(function(int $key, PlayerTeamInterface $value): bool {
            return PlayerTeamInterface::INACTIVE_STATE !== $value->getState();
        });
    }

    public function getActualValue(): ?float
    {
        return $this->getActualActiveStatus()->getSellingValue() ?? $this->getActualActiveStatus()->getAmountValue();
    }

    public function getActualTeam(): ?TeamInterface
    {
        return $this->getActualActiveStatus()?->getTeam();
    }

    public function getCountActiveBids() : int
    {
        return $this->getActualActiveStatus()->getCountActiveBid();
    }

    public function isActive(): bool
    {
        return !is_null($this->getActualTeam());
    }
}
