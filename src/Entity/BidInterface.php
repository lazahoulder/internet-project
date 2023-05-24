<?php

namespace App\Entity;

interface BidInterface
{
    public const ACTIVE_STATUS = 'active';
    public const CLOSED_STATUS = 'closed';

    public function getId(): ?int;

    public function getValue(): ?float;

    public function setValue(float $value): \App\Entity\Bid;

    public function getPlayerTeam(): ?PlayerTeam;

    public function setPlayerTeam(?PlayerTeam $playerTeam): \App\Entity\Bid;

    public function getTeam(): ?Team;

    public function setTeam(?Team $team): \App\Entity\Bid;

    public function isClosed(): ?bool;

    public function setClosed(?bool $closed): self;
}