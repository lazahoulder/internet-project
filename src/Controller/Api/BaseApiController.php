<?php

namespace App\Controller\Api;

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
            $messages[$violation->getPropertyPath()][] = $violation->getMessage();
        }
        return $this->json($messages, Response::HTTP_BAD_REQUEST);
    }
}