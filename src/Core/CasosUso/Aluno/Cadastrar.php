<?php

declare(strict_types=1);

namespace SecretariaFiap\Core\CasosUso\Aluno;

use DateTime;
use SecretariaFiap\Core\Contratos\Repositorio\AlunoRepositorio;
use SecretariaFiap\Core\Entidade\Aluno;
use SecretariaFiap\Core\Entidade\Senha;

class Cadastrar
{
    private AlunoRepositorio $repositorio;

    public function __construct(AlunoRepositorio $alunoRepositorio)
    {
        $this->repositorio = $alunoRepositorio;
    }

    public function executar(InputObject $inputObject): OutputObject
    {
        $aluno = new Aluno(
            $inputObject->nome,
            $inputObject->cpf,
            $inputObject->email,
            Senha::criar($inputObject->senha),
            new DateTime($inputObject->dataNascimento)
        );

        $resultado = $this->repositorio->cadastrar($aluno);

        return OutputObject::create([
            'uuid' => $resultado->getUuid(),
            'nome' => $resultado->getNome(),
            'cpf' => $resultado->getCpf(),
            'email' => $resultado->getEmail(),
            'data_nascimento' => $resultado->getDataNascimento()->format('Y-m-d')
        ]);
    }

}
