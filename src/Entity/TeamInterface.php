<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;

interface TeamInterface
{
    public function getId(): ?int;

    public function getName(): ?string;

    public function setName(string $name): TeamInterface;

    public function getAcountBalance(): ?float;

    public function setAcountBalance(?float $acountBalance): TeamInterface;

    public function getCountry(): ?string;

    public function setCountry(string $country): TeamInterface;

    /**
     * @return Collection<int, PlayerTeamInterface>
     */
    public function getPlayerTeams(): Collection;

    public function addPlayerTeam(PlayerTeamInterface $playerTeam): TeamInterface;

    public function removePlayerTeam(PlayerTeamInterface $playerTeam): TeamInterface;

    public function getActivePlayers() : array;

    public function increaseBalance(float $amount) : TeamInterface;

    public function decreaseBalance(float $amount) : TeamInterface;

    public function getCountActivePlayers(): int;

    public function getCountInMarketPlayers(): int;
}