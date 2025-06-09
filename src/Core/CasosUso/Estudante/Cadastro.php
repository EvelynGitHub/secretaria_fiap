<?php

declare(strict_types=1);

namespace SecretariaFiap\Core\CasosUso\Estudante;

use SecretariaFiap\Core\Contratos\Repositorio\EstudanteRepositorio;
use SecretariaFiap\Core\Entidade\Estudante;

class Cadastro
{
    private EstudanteRepositorio $repositorio;

    public function __construct(EstudanteRepositorio $estudanteRepositorio)
    {
        $this->repositorio = $estudanteRepositorio;
    }

    public function executar(InputObject $inputObject): OutputObject
    {
        $estudante = new Estudante();
        $estudante->setNome($inputObject->nome);

        $resultado = $this->repositorio->cadastrar($estudante);

        return OutputObject::create([
            'uuid' => $estudante->getUuid(),
            'nome' => 'Eu',
            'cpf' => '123',
            'email' => 'email',
            'data_nascimento' => '1999-09-09'
        ]);
    }

}
