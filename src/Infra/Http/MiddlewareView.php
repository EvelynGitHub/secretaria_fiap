<?php

declare(strict_types=1);

namespace SecretariaFiap\Infra\Http;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;

class MiddlewareView
{
    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $cookies = $request->getCookieParams();
        $token = $cookies['auth_token'] ?? null;

        // Se o token existe segue para o Middleware::class
        if ($token) {
            return $handler->handle($request);
        }
        // Se não existe redireciona
        return (new Response())->withHeader('Location', '/login')->withStatus(302);

    }

}