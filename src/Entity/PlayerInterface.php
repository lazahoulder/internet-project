<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;

interface PlayerInterface
{
    public function getId(): ?int;

    public function getName(): ?string;

    public function setName(string $name): PlayerInterface;

    public function getSurname(): ?string;

    public function setSurname(string $surname): PlayerInterface;

    /**
     * @return Collection<int, PlayerTeamInterface>
     */
    public function getPlayerTeams(): Collection;

    public function addPlayerTeam(PlayerTeamInterface $playerTeam): PlayerInterface;

    public function removePlayerTeam(PlayerTeamInterface $playerTeam): PlayerInterface;

    public function getActualActiveStatus() : ?PlayerTeamInterface;

    public function getActualTeam(): ?TeamInterface;

    public function getActualValue() : ?float;

    public function isActive(): bool;
}