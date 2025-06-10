<?php

declare(strict_types=1);

namespace SecretariaFiap\Core\CasosUso\Turma;

use Exception;
use SecretariaFiap\Core\Contratos\Repositorio\TurmaRepositorio;

class Obter
{
    private TurmaRepositorio $repositorio;

    public function __construct(TurmaRepositorio $turmaRepositorio)
    {
        $this->repositorio = $turmaRepositorio;
    }

    public function executar(string $uuid): OutputObject
    {
        $turma = $this->repositorio->buscarPorUuid($uuid);

        if (empty($turma)) {
            throw new Exception("A turma informada não existe.", 404);
        }

        return OutputObject::create([
            'uuid' => $turma->getUuid(),
            'nome' => $turma->getNome(),
            'descricao' => $turma->getDescricao()
        ]);
    }
}