<?php

declare(strict_types=1);

namespace SecretariaFiap\Core\CasosUso\Aluno;

use Exception;
use SecretariaFiap\Core\Contratos\Repositorio\AlunoRepositorio;

class Obter
{
    private AlunoRepositorio $repositorio;

    public function __construct(AlunoRepositorio $alunoRepositorio)
    {
        $this->repositorio = $alunoRepositorio;
    }

    public function executar(string $uuid): OutputObject
    {
        $aluno = $this->repositorio->buscarPorUuid($uuid);

        if (empty($aluno)) {
            throw new Exception("O aluno informado não existe.", 404);
        }

        return OutputObject::create([
            'uuid' => $aluno->getUuid(),
            'nome' => $aluno->getNome(),
            'cpf' => $aluno->getCpf(),
            'email' => $aluno->getEmail(),
            'data_nascimento' => $aluno->getDataNascimento()->format('Y-m-d')
        ]);
    }
}