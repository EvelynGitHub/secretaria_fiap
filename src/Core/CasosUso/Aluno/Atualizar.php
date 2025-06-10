<?php

declare(strict_types=1);

namespace SecretariaFiap\Core\CasosUso\Aluno;

use DateTime;
use Exception;
use SecretariaFiap\Core\Contratos\Repositorio\AlunoRepositorio;
use SecretariaFiap\Core\Entidade\Aluno;
use SecretariaFiap\Core\Entidade\Senha;

class Atualizar
{
    private AlunoRepositorio $repositorio;

    public function __construct(AlunoRepositorio $alunoRepositorio)
    {
        $this->repositorio = $alunoRepositorio;
    }

    public function executar(InputObject $inputObject): OutputObject
    {

        $alunoExiste = $this->repositorio->buscarPorUuid($inputObject->uuid);

        if (empty($alunoExiste)) {
            throw new Exception("O aluno informado não existe.", 404);
        }

        if (!empty($inputObject->senha)) {
            // Caso esteja atualizando a senha
            $senha = Senha::criar($inputObject->senha);
        } else {
            // Caso esteja mantendo a senha antiga
            $senha = Senha::criarAPartirDoHash($alunoExiste->getSenha());
        }

        $aluno = new Aluno(
            $inputObject->nome ?? $alunoExiste->getNome(),
            $inputObject->cpf ?? $alunoExiste->getCpf(),
            $inputObject->email ?? $alunoExiste->getEmail(),
            $senha,
            new DateTime($inputObject->dataNascimento) ?? $alunoExiste->getDataNascimento(),
            $inputObject->uuid //UUID para atualização
        );

        $sucesso = $this->repositorio->editar($aluno);

        if (!$sucesso) {
            throw new Exception("Não foi possível atualizar o Aluno informado.", 500);
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