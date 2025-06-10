<?php

declare(strict_types=1);

namespace SecretariaFiap\Core\CasosUso\Turma;

use SecretariaFiap\Core\Contratos\Repositorio\TurmaRepositorio;

class Remover
{
    private TurmaRepositorio $repositorio;

    public function __construct(TurmaRepositorio $turmaRepositorio)
    {
        $this->repositorio = $turmaRepositorio;
    }

    public function executar(string $uuid): string
    {
        $sucesso = $this->repositorio->deletar($uuid);

        if ($sucesso) {
            return "Turma removida com sucesso!";
        }

        return "Não foi possível remover essa turma.";
    }
}