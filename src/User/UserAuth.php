<?php

declare(strict_types=1);

namespace Yiisoft\Yii\Web\User;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Auth\AuthInterface;
use Yiisoft\Auth\IdentityInterface;
use Yiisoft\Http\Status;

final class UserAuth implements AuthInterface
{
    private string $authUrl = '/login';
    private User $user;
    private ResponseFactoryInterface $responseFactory;

    public function __construct(User $user, ResponseFactoryInterface $responseFactory)
    {
        $this->user = $user;
        $this->responseFactory = $responseFactory;
    }

    public function authenticate(ServerRequestInterface $request): ?IdentityInterface
    {
        if ($this->user->isGuest()) {
            return null;
        }

        return $this->user->getIdentity();
    }

    public function challenge(ResponseInterface $response): ResponseInterface
    {
        return $this->responseFactory->createResponse(Status::FOUND)->withHeader('Location', $this->authUrl);
    }

    public function setAuthUrl(string $url): void
    {
        $this->authUrl = $url;
    }
}
