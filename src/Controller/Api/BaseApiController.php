<?php

namespace App\Controller\Api;

use App\DataTransformer\OutputHandlar\OutputTransformerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class BaseApiController extends AbstractController
{
    public function __construct(protected SerializerInterface $serializer)
    {
    }

    protected function handelError(ConstraintViolationListInterface $errors)
    {
        $messages = [];
        foreach ($errors as $violation) {
            $messages[$violation->getPropertyPath()] = $violation->getMessage();
        }

        $extractedArray = [];
        foreach ($messages as $key => $value) {
            $parts = explode('.', $key);
            $currentArray = &$extractedArray;

            foreach ($parts as $part) {
                $matches = [];
                preg_match('/(.+)\[(\d+)\]/', $part, $matches);

                if (count($matches) === 3) {
                    $currentArray = &$currentArray[$matches[1]][$matches[2]];
                } else {
                    $currentArray = &$currentArray[$part];
                }
            }

            $currentArray = $value;
        }

        return $this->json($extractedArray, Response::HTTP_BAD_REQUEST);
    }

    protected function handleListData(OutputTransformerInterface $outputTransformer, PaginationInterface $pagination)
    {
        return [
            'results' => $outputTransformer->listNormalize($pagination->getItems()),
            'page' => $pagination->getCurrentPageNumber(),
            'pageNumber' => ceil($pagination->getTotalItemCount() / $pagination->getItemNumberPerPage()),
            'total' => $pagination->getTotalItemCount(),
        ];
    }
}