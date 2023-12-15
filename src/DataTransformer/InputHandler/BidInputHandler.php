<?php

namespace App\DataTransformer\InputHandler;

use App\Dto\IntputDTO\BidInput;
use App\Entity\Bid;
use App\Repository\BidRepository;
use App\Repository\PlayerTeamRepository;
use App\Repository\TeamRepository;
use Exception;

class BidInputHandler implements InputHandlerInterface
{
    public function __construct(
        private PlayerTeamRepository $playerTeamRepository,
        private TeamRepository $teamRepository,
        private BidRepository $bidRepository,
    )
    {

    }

    /**
     * @param BidInput $data
     * @return mixed
     */
    public function handle($data): mixed
    {
        $playerTeam = $this->playerTeamRepository->find($data->playerTeamId);
        if (is_null($playerTeam)) {
            throw new Exception(sprintf('Player with id %s not found', $data->playerTeamId));
        }

        $team = $this->teamRepository->find($data->teamId);

        if (is_null($team)) {
            throw new Exception(sprintf('Team with id %s not found', $data->teamId));
        }

        $bid = $this->bidRepository->findOneBy(['playerTeam' => $playerTeam, 'team' => $team]) ?? new Bid();

        $bid
            ->setTeam($team)
            ->setPlayerTeam($playerTeam)
            ->setValue($data->value)
            ->setClosed(false);

        if (is_null($bid->getDate())) {
            $bid->setDate(new \DateTime());
        }

        $this->bidRepository->save($bid, true);

        return $bid;
    }
}