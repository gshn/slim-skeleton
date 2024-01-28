<?php

namespace App\Action\User;

use App\Domain\User\Service\UserCreator;
use App\Renderer\JsonRenderer;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class UserCreatorAction
{
    private UserCreator $userCreator;
    private JsonRenderer $renderer;

    public function __construct(UserCreator $creator, JsonRenderer $renderer)
    {
        $this->userCreator = $creator;
        $this->renderer = $renderer;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        $data = (array)$request->getParsedBody();

        $token = $this->userCreator->createUser($data);

        return $this->renderer
            ->json($response, ['token' => $token])
            ->withStatus(StatusCodeInterface::STATUS_CREATED);
    }
}
