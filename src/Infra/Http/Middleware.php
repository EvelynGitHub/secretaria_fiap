<?php

declare(strict_types=1);

namespace SecretariaFiap\Infra\Http;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RuntimeException;
use SecretariaFiap\Helpers\TokenHelper;
use Slim\Psr7\Response;

class Middleware
{
    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $response = new Response();
            $httpHeader = $request->getHeaderLine('Authorization');

            $jwt = explode(' ', $httpHeader)[1] ?? null;

            // Tenta obter pelo Authorization (para o swagger), se não conseguir tenta pelos cookies
            if (empty($jwt)) {
                // 1. Obter os cookies da requisição
                $cookies = $request->getCookieParams();
                // 2. Extrair o token JWT do cookie 'auth_token'
                $jwt = $cookies['auth_token'] ?? null;
            }

            if (is_null($jwt)) {
                throw new RuntimeException('Token não informado.', 401);
            }

            $payload = TokenHelper::decodificar($jwt);

            if (!$payload) {
                throw new Exception('Token inválido ou expirado.', 401);
            }

            $response = $handler->handle($request);

            return $response;

        } catch (\Throwable $e) {
            return $this->setError($e);
        }
    }

    public function setError($objError)
    {
        $response = new Response();
        try {

            $exception = [];

            $exception["message"] = $objError->getMessage();
            $exception["status_code"] = $objError->getCode();
            $exception["file"] = $objError->getFile();
            $exception["line"] = $objError->getLine();

            if ($objError instanceof RuntimeException) {
                // Se for um erro de RuntimeException, não redireciona para o login
                $response = $response->withHeader('Location', '/login')
                    ->withStatus($objError->getCode());

                $response->getBody()->write(json_encode($exception));
            } elseif ($objError instanceof Exception) {
                // Se for Exception ('Token inválido ou expirado.'), destrói o cookie e redireciona para o login
                $response = $response
                    ->withHeader('Set-Cookie', 'auth_token=deleted; Path=/; HttpOnly; Expires=Thu, 01 Jan 1970 00:00:00 GMT; Max-Age=0; SameSite=None; Secure')
                    ->withHeader('Location', '/login')
                    ->withStatus($objError->getCode());
                $response->getBody()->write(json_encode($exception));
            }


            return $response;
        } catch (\Throwable $th) {
            $exception["message"] = $th->getMessage();
            $exception["status_code"] = $th->getCode();
            $exception["file"] = $th->getFile();
            $exception["line"] = $th->getLine();

            $response->withHeader('Location', '/login')
                ->withStatus($objError->getCode())
                ->getBody()->write(json_encode($exception));

            return $response;
        }
    }
}