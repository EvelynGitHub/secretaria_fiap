<?php

declare(strict_types=1);

namespace SecretariaFiap\Core\CasosUso\Turma;

use SecretariaFiap\Core\Contratos\Repositorio\TurmaRepositorio;
use SecretariaFiap\Core\Entidade\Turma;

class Cadastrar
{
    private TurmaRepositorio $repositorio;

    public function __construct(TurmaRepositorio $turmaRepositorio)
    {
        $this->repositorio = $turmaRepositorio;
    }

    public function executar(InputObject $inputObject): OutputObject
    {
        $turma = new Turma(
            $inputObject->nome,
            $inputObject->descricao
        );

        $resultado = $this->repositorio->cadastrar($turma);

        return OutputObject::create([
            'uuid' => $resultado->getUuid(),
            'nome' => $resultado->getNome(),
            'descricao' => $resultado->getDescricao()
        ]);
    }

}
