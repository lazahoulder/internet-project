<?php

namespace App\Controller\Api;

use App\Entity\Bid;
use App\Repository\BidRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('api/bids')]
class BidApi extends BaseApiController
{
    public function __construct(
        SerializerInterface   $serializer,
        private BidRepository $repository
    )
    {
        parent::__construct($serializer);
    }

    #[Route('/', name: 'api_bids_list', methods: ['GET'])]
    public function listBids(Request $request)
    {

    }

    #[Route('/', name: 'api_bids_create', methods: ['POST'])]
    public function createBids(Request $request)
    {

    }

    #[Route('/{id}', name: 'api_bids_update', methods: ['PUT'])]
    public function updateBids(Request $request, Bid $bid)
    {

    }

    #[Route('/{id}', name: 'api_bids_close', methods: ['PATCH'])]
    public function closeBids(Request $request, Bid $bid)
    {

    }

    #[Route('/{id}', name: 'api_bids_delete', methods: ['DELETE'])]
    public function deleteBids(Request $request, Bid $bid)
    {
        $this->repository->remove($bid, true);
        $response = new Response();
        $response->setStatusCode(Response::HTTP_NO_CONTENT);

        return $response;
    }
}