<?php

declare(strict_types=1);

namespace SecretariaFiap\Adapter\Controller;

use SecretariaFiap\Core\CasosUso\Aluno\Listar;
use SecretariaFiap\Helpers\HttpResposta;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AlunoController
{
    public Listar $casoUsoListar;

    public function __construct(Listar $casoUsoListar)
    {
        $this->casoUsoListar = $casoUsoListar;
    }

    public function listar(Request $request, Response $response): Response
    {
        try {
            $outputLista = $this->casoUsoListar->executar();

            $dados = $outputLista->jsonSerialize();

            return HttpResposta::sucesso($dados, 200);
        } catch (\Throwable $th) {
            return HttpResposta::erro($th);
        }
    }
}
