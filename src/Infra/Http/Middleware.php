<?php

declare(strict_types=1);

namespace SecretariaFiap\Infra\Http;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;

class Middleware
{
    // https://www.slimframework.com/docs/v4/objects/request.html#attributes
    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $response = new Response();
            $httpHeader = $request->getHeaderLine('Authorization');

            $jwt = explode(' ', $httpHeader)[1] ?? null;

            if (is_null($jwt)) {
                throw new Exception('Token não informado.', 401);
            }

            $tokenAdmin = $_SESSION['jwt'];

            if ($jwt !== $tokenAdmin) {
                throw new Exception('Token inválido.', 401);
            }

            return $response;

        } catch (Exception $e) {
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

            $response->getBody()->write(json_encode($exception));
            $response->withStatus($objError->getCode());

            return $response;
        } catch (\Throwable $th) {
            $exception["message"] = $th->getMessage();
            $exception["status_code"] = $th->getCode();
            $exception["file"] = $th->getFile();
            $exception["line"] = $th->getLine();

            $response->getBody()->write(json_encode($exception));
            $response->withStatus($objError->getCode());

            return $response;
        }
    }
}