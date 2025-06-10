<?php

declare(strict_types=1);

namespace SecretariaFiap\Core\CasosUso\Aluno;

use SecretariaFiap\Core\Contratos\Repositorio\AlunoRepositorio;
use SecretariaFiap\Core\Entidade\Aluno;
use SecretariaFiap\Helpers\OutputPaginacaoObject;

class Listar
{
    private AlunoRepositorio $repositorio;

    public function __construct(AlunoRepositorio $alunoRepositorio)
    {
        $this->repositorio = $alunoRepositorio;
    }

    public function executar(string $nome = "", int $limit = 10, int $offset = 0): OutputPaginacaoObject
    {
        // 1. Obtém a paginação de entidades (Aluno) do repositório
        /** @var \SecretariaFiap\Helpers\Paginacao<Aluno> $alunosPaginados */
        $alunosPaginados = $this->repositorio->listarPorFiltros($nome, $offset, $limit);

        // 2. Define o callable de mapeamento
        $mapper = function (Aluno $aluno): OutputObject {
            return OutputObject::create([
                'uuid' => $aluno->getUuid(),
                'nome' => $aluno->getNome(),
                'cpf' => $aluno->getCpf(),
                'email' => $aluno->getEmail(),
                'data_nascimento' => $aluno->getDataNascimento()->format('Y-m-d')
            ]);
        };

        // 3. Cria e retorna o OutputPaginacaoObject, passando a Paginacao de entidades e o callable
        return OutputPaginacaoObject::create($alunosPaginados, $mapper);
    }
}