<?php

declare(strict_types=1);

namespace SecretariaFiap\Core\CasosUso\Turma;

use DateTime;
use Exception;
use SecretariaFiap\Core\Contratos\Repositorio\TurmaRepositorio;
use SecretariaFiap\Core\Entidade\Turma;
use SecretariaFiap\Core\Entidade\Senha;

class Atualizar
{
    private TurmaRepositorio $repositorio;

    public function __construct(TurmaRepositorio $turmaRepositorio)
    {
        $this->repositorio = $turmaRepositorio;
    }

    public function executar(InputObject $inputObject): OutputObject
    {

        $turmaExiste = $this->repositorio->buscarPorUuid($inputObject->uuid);

        if (empty($turmaExiste)) {
            throw new Exception("A turma informada não existe.", 404);
        }

        $turma = new Turma(
            $inputObject->nome ?? $turmaExiste->getNome(),
            $inputObject->descricao ?: $turmaExiste->getDescricao(),
            $inputObject->uuid //UUID para atualização
        );

        $sucesso = $this->repositorio->editar($turma);

        if (!$sucesso) {
            throw new Exception("Não foi possível atualizar a Turma informada.", 500);
        }

        return OutputObject::create([
            'uuid' => $turma->getUuid(),
            'nome' => $turma->getNome(),
            'descricao' => $turma->getDescricao(),
        ]);
    }
}