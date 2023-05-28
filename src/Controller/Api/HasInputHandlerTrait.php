<?php

namespace App\Controller\Api;

use App\DataTransformer\InputHandler\InputHandlerInterface;
use App\DataTransformer\InputHandler\PlayerTeamInputHandler;
use App\DataTransformer\OutputHandler\OutputTransformerInterface;
use App\Dto\IntputDTO\PlayerTeamInput;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

trait HasInputHandlerTrait
{
    /**
     * @param ValidatorInterface $validator
     * @param mixed $input
     * @param InputHandlerInterface $inputHandler
     * @return JsonResponse
     */
    protected function handleInput(
        ValidatorInterface         $validator,
        mixed                      $input,
        InputHandlerInterface      $inputHandler,
        OutputTransformerInterface $outputTransformer,
        int $httpCode = 201
    ): JsonResponse
    {
        $errors = $validator->validate($input);

        if (count($errors)) {
            return $this->handelError($errors);
        }

        try {
            $data = $inputHandler->handle($input);
        } catch (\Exception $e) {
            return $this->json(
                [
                    'message' => $e->getMessage()
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return $this->json(
            $outputTransformer->normalize($data),
            $httpCode
        );
    }
}