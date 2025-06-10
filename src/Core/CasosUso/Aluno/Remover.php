<?php

declare(strict_types=1);

namespace SecretariaFiap\Core\CasosUso\Aluno;

use SecretariaFiap\Core\Contratos\Repositorio\AlunoRepositorio;

class Remover
{
    private AlunoRepositorio $repositorio;

    public function __construct(AlunoRepositorio $alunoRepositorio)
    {
        $this->repositorio = $alunoRepositorio;
    }

    public function executar(string $uuid): string
    {
        // Pode adicionar validação para verificar se o aluno existe
        $sucesso = $this->repositorio->deletar($uuid);

        if ($sucesso) {
            return "Aluno removido com sucesso!";
        }

        return "Não foi possível remover esse aluno.";
    }
}