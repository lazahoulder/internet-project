<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;

interface PlayerTeamInterface
{
    const ACTIVE_STATE = 'active';
    const IN_MARKET_STATE = 'in_market';
    const INACTIVE_STATE = 'inactive';
    public function getId(): ?int;

    public function getStartDate(): ?\DateTimeInterface;

    public function setStartDate(\DateTimeInterface $startDate): PlayerTeamInterface;

    public function getEndDate(): ?\DateTimeInterface;

    public function setEndDate(?\DateTimeInterface $endDate): PlayerTeamInterface;

    public function getAmountValue(): ?float;

    public function setAmountValue(?float $amountValue): PlayerTeamInterface;

    public function getState(): ?string;

    public function setState(string $state): PlayerTeamInterface;

    public function getPlayer(): ?PlayerInterface;

    public function setPlayer(?PlayerInterface $player): PlayerTeamInterface;

    public function getTeam(): ?TeamInterface;

    public function setTeam(?TeamInterface $team): self;

    public function getSellingValue(): ?float;

    public function setSellingValue(?float $sellingValue): self;

    public function getExpectedEndDate(): ?\DateTimeInterface;

    public function setExpectedEndDate(?\DateTimeInterface $expectedEndDate): self;

    public function publishInMarketPlayer(float $sellValue) : self;

    public function closePlayerContract(): void;

    public function addBid(Bid $bid): self;

    public function removeBid(Bid $bid): self;

    /**
     * @return Collection<int, Bid>
     */
    public function getBids(): Collection;

    public function getCountActiveBid(): int;
}