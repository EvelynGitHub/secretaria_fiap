<?php

declare(strict_types=1);

namespace SecretariaFiap\Core\CasosUso\Turma;

use SecretariaFiap\Core\Contratos\Repositorio\TurmaRepositorio;
use SecretariaFiap\Core\Entidade\Turma;
use SecretariaFiap\Helpers\OutputPaginacaoObject;

class Listar
{
    private TurmaRepositorio $repositorio;

    public function __construct(TurmaRepositorio $turmaRepositorio)
    {
        $this->repositorio = $turmaRepositorio;
    }

    public function executar(string $nome = "", int $limit = 10, int $offset = 0): OutputPaginacaoObject
    {
        $turmasPaginadas = $this->repositorio->listarPorFiltros($nome, $offset, $limit);

        $mapper = fn(Turma $turma): OutputObject =>
            OutputObject::create([
                'uuid' => $turma->getUuid(),
                'nome' => $turma->getNome(),
                'descricao' => $turma->getDescricao()
            ]);

        return OutputPaginacaoObject::create($turmasPaginadas, $mapper);
    }
}