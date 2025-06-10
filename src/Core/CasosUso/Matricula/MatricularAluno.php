<?php

declare(strict_types=1);

namespace SecretariaFiap\Core\CasosUso\Matricula;

use SecretariaFiap\Core\Contratos\Repositorio\AlunoRepositorio;
use SecretariaFiap\Core\Contratos\Repositorio\MatriculaRepositorio;
use SecretariaFiap\Core\Contratos\Repositorio\TurmaRepositorio;
use SecretariaFiap\Core\Entidade\Matricula;

class MatricularAluno
{
    public function __construct(
        private AlunoRepositorio $alunoRepositorio,
        private TurmaRepositorio $turmaRepositorio,
        private MatriculaRepositorio $matriculaRepositorio
    ) {
    }

    public function executar(InputObject $input): OutputObject
    {
        $aluno = $this->alunoRepositorio->buscarPorUuid($input->uuidAluno);
        if (!$aluno) {
            return new OutputObject(false, "Aluno não encontrado.");
        }

        $turma = $this->turmaRepositorio->buscarPorUuid($input->uuidTurma);
        if (!$turma) {
            return new OutputObject(false, "Turma não encontrada.");
        }

        if ($this->matriculaRepositorio->existe($aluno->getId(), $turma->getId())) {
            return new OutputObject(false, "Aluno já está matriculado nesta turma.");
        }

        $matricula = new Matricula($aluno, $turma);
        $this->matriculaRepositorio->salvar($matricula);

        return new OutputObject(true, "{$aluno->getNome()} matriculado(a) em {$turma->getNome()} com sucesso.");
    }
}
