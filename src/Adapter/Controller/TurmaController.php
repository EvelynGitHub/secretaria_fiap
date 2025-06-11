<?php

declare(strict_types=1);

namespace SecretariaFiap\Adapter\Controller;

use Exception;
use SecretariaFiap\Core\CasosUso\Turma\Atualizar;
use SecretariaFiap\Core\CasosUso\Turma\Cadastrar;
use SecretariaFiap\Core\CasosUso\Turma\InputObject;
use SecretariaFiap\Core\CasosUso\Turma\Listar;
use SecretariaFiap\Core\CasosUso\Turma\Obter;
use SecretariaFiap\Core\CasosUso\Turma\Remover;
use SecretariaFiap\Helpers\HttpResposta;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class TurmaController
{

    public Listar $casoUsoListar;
    public Cadastrar $casoUsoCadastrar;
    public Atualizar $casoUsoAtualizar;
    public Remover $casoUsoRemover;
    public Obter $casoUsoObter;

    public function __construct(
        Listar $casoUsoListar,
        Cadastrar $casoUsoCadastrar,
        Atualizar $casoUsoAtualizar,
        Remover $casoUsoRemover,
        Obter $casoUsoObter
    ) {
        $this->casoUsoListar = $casoUsoListar;
        $this->casoUsoCadastrar = $casoUsoCadastrar;
        $this->casoUsoAtualizar = $casoUsoAtualizar;
        $this->casoUsoRemover = $casoUsoRemover;
        $this->casoUsoObter = $casoUsoObter;
    }


    public function listar(Request $request, Response $response): Response
    {
        try {
            $params = $request->getQueryParams();
            $nome = $params['nome'] ?? '';
            $limit = (int) ($params['limit'] ?? 10);
            $offset = (int) ($params['offset'] ?? 0);

            $outputLista = $this->casoUsoListar->executar($nome, $limit, $offset);

            $dados = $outputLista->jsonSerialize();

            return HttpResposta::sucesso($dados, 200);
        } catch (\Throwable $th) {
            return HttpResposta::erro($th);
        }
    }

    public function cadastrar(Request $request, Response $response): Response
    {
        try {
            $body = $request->getParsedBody();
            $input = InputObject::create($body);

            $output = $this->casoUsoCadastrar->executar($input);

            return HttpResposta::sucesso($output->toArray(), 201);
        } catch (\Throwable $th) {
            return HttpResposta::erro($th);
        }
    }

    public function obter(Request $request, Response $response, array $args): Response
    {
        try {

            $uuid = $this->verificarUuidRequest($args);

            $output = $this->casoUsoObter->executar($uuid);

            return HttpResposta::sucesso($output->toArray(), 200);
        } catch (\Throwable $th) {
            return HttpResposta::erro($th);
        }
    }


    public function atualizar(Request $request, Response $response, array $args): Response
    {
        try {

            $uuid = $this->verificarUuidRequest($args);
            $body = $request->getParsedBody();
            $body['uuid'] = $uuid;

            $input = InputObject::create($body);

            $output = $this->casoUsoAtualizar->executar($input);

            return HttpResposta::sucesso($output->toArray(), 200);
        } catch (\Throwable $th) {
            return HttpResposta::erro($th);
        }
    }

    public function remover(Request $request, Response $response, array $args): Response
    {
        try {
            $uuid = $this->verificarUuidRequest($args);

            $output = $this->casoUsoRemover->executar($uuid);

            return HttpResposta::sucesso($output, 204);
        } catch (\Throwable $th) {
            return HttpResposta::erro($th);
        }
    }

    private function verificarUuidRequest(array $dadosRequest, string $key = "uuid"): string
    {
        if (empty($dadosRequest[$key])) {
            throw new Exception("UUID não encontrado ", 400);
        }

        return $dadosRequest[$key];
    }
}
