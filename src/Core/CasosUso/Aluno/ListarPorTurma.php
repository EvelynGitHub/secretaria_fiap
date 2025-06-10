<?php

declare(strict_types=1);

namespace SecretariaFiap\Core\CasosUso\Aluno;

use SecretariaFiap\Core\Contratos\Repositorio\AlunoRepositorio;
use SecretariaFiap\Core\Entidade\Aluno;
use SecretariaFiap\Helpers\OutputPaginacaoObject;

class ListarPorTurma
{
    private AlunoRepositorio $repositorio;

    public function __construct(AlunoRepositorio $alunoRepositorio)
    {
        $this->repositorio = $alunoRepositorio;
    }

    public function executar(string $uuidTurma, int $limit = 10, int $offset = 0): OutputPaginacaoObject
    {
        // Pode-se adicionar validações para o UUID da turma, se necessário.
        if (empty($uuidTurma)) {
            throw new \InvalidArgumentException("O UUID da turma não pode ser vazio.");
        }

        $alunosPaginados = $this->repositorio->listarPorTurma($uuidTurma, $offset, $limit);

        $mapper = function (Aluno $aluno): OutputObject {
            return OutputObject::create([
                'uuid' => $aluno->getUuid(),
                'nome' => $aluno->getNome(),
                'cpf' => $aluno->getCpf(),
                'email' => $aluno->getEmail(),
                'data_nascimento' => $aluno->getDataNascimento()->format('Y-m-d')
            ]);
        };

        return OutputPaginacaoObject::create($alunosPaginados, $mapper);
    }
}