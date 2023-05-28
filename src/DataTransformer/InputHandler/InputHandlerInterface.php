<?php

namespace App\DataTransformer\InputHandler;

interface InputHandlerInterface
{
    public function handle(mixed $data) : mixed;
}