<?php

declare(strict_types=1);

namespace SecretariaFiap\Adapter\Controller;

use SecretariaFiap\Core\CasosUso\Matricula\InputObject;
use SecretariaFiap\Core\CasosUso\Matricula\MatricularAluno;
use SecretariaFiap\Helpers\HttpResposta;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class MatriculaController
{
    public MatricularAluno $casoUsoMatricular;

    public function __construct(MatricularAluno $casoUsoMatricular)
    {
        $this->casoUsoMatricular = $casoUsoMatricular;
    }

    public function matricularAluno(Request $request, Response $response): Response
    {
        try {
            $body = $request->getParsedBody();

            $input = InputObject::create($body);

            $output = $this->casoUsoMatricular->executar($input);

            return HttpResposta::sucesso($output, 201);
        } catch (\Throwable $th) {
            return HttpResposta::erro($th);
        }
    }
}
