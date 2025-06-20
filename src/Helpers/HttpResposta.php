<?php

declare(strict_types=1);

namespace SecretariaFiap\Helpers;

use Slim\Psr7\Response;
use Throwable;

class HttpResposta
{

    public static function sucesso(mixed $dados, int $cod): Response
    {
        $response = new Response();
        try {

            $response->getBody()->write(json_encode($dados));
            $response->withStatus($cod);

            return $response;
        } catch (Throwable $th) {
            return self::erro($th);
        }
    }

    public static function erro(Throwable $objError): Response
    {
        $response = new Response();
        try {

            $exception = [];

            $exception["message"] = $objError->getMessage();
            $exception["status_code"] = $objError->getCode();
            $exception["file"] = $objError->getFile();
            $exception["line"] = $objError->getLine();

            $codErro = $objError->getCode() == 0 ? 400 : $objError->getCode();
            $response->getBody()->write(json_encode($exception));
            $response->withStatus($codErro);

            return $response;
        } catch (Throwable $th) {
            $exception["message"] = $th->getMessage();
            $exception["status_code"] = $th->getCode();
            $exception["file"] = $th->getFile();
            $exception["line"] = $th->getLine();

            $codErro = $th->getCode() == 0 ? 500 : $th->getCode();

            $response->getBody()->write(json_encode($exception));
            $response->withStatus($codErro);

            return $response;
        }
    }
}
