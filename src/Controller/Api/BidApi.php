<?php

namespace App\Controller\Api;

use App\DataTransformer\InputHandler\BidInputHandler;
use App\DataTransformer\OutputHandler\BidsOutputTransformer;
use App\Dto\IntputDTO\BidInput;
use App\Entity\Bid;
use App\Repository\BidRepository;
use App\Service\BidService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('api/bids')]
class BidApi extends BaseApiController
{
    use HasInputHandlerTrait;
    public function __construct(
        SerializerInterface           $serializer,
        PaginatorInterface            $paginator,
        private BidRepository         $repository,
        private BidsOutputTransformer $bidsOutputTransformer,
        private BidService            $bidService
    )
    {
        parent::__construct($serializer, $paginator);
    }

    #[Route('/', name: 'api_bids_list', methods: ['GET'])]
    public function listBids(Request $request)
    {
        $pagination = $this->paginator->paginate(
            $this->repository->findActiveBidsQuery($request->get('playerTeamId')),
            (int)$request->get('page', 1),
            (int)$request->get('limit', 10)
        );

        $data = $this->handleListData($this->bidsOutputTransformer, $pagination);

        return $this->json($data, Response::HTTP_OK);
    }

    #[Route('/', name: 'api_bids_save', methods: ['POST'])]
    public function saveBids(Request $request, BidInputHandler $inputHandler, ValidatorInterface $validator)
    {
        /** @var BidInput $input */
        $input = $this
            ->serializer
            ->deserialize($request->getContent(), BidInput::class, 'json');


        return $this->handleInput(
            $validator,
            $input,
            $inputHandler,
            $this->bidsOutputTransformer,
            Response::HTTP_CREATED);

    }

    #[Route('/{id}', name: 'api_bids_delete', methods: ['DELETE'])]
    public function deleteBids(Bid $bid)
    {
        $this->repository->remove($bid, true);
        $response = new Response();
        $response->setStatusCode(Response::HTTP_NO_CONTENT);

        return $response;
    }

    #[Route('/{id}', name: 'api_bids_accept', methods: ['PATCH'])]
    public function acceptBids(Bid $bid): JsonResponse|Response
    {
        try {
            $this->bidService->acceptBid($bid);

            $response = new Response();
            $response->setStatusCode(Response::HTTP_NO_CONTENT);

            return $response;

        } catch (\Exception $exception) {
            $data=['message' => $exception->getMessage()];

            return $this->json($data, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}