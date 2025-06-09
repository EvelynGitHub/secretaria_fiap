<?php

declare(strict_types=1);

namespace SecretariaFiap\Core\Entidade;

use SecretariaFiap\Helpers\GeradorUuid;

class Matricula
{
    private string $uuid;
    private Aluno $aluno;
    private Turma $turma;
    private \DateTime $dataMatricula;

    public function __construct(
        Aluno $aluno,
        Turma $turma,
        ?string $uuid = null
    ) {
        $this->aluno = $aluno;
        $this->turma = $turma;
        $this->dataMatricula = new \DateTime();
        $this->uuid = $uuid ?? GeradorUuid::gerar();
    }

    public function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getAluno(): Aluno
    {
        return $this->aluno;
    }
    public function getTurma(): Turma
    {
        return $this->turma;
    }
    public function getDataMatricula(): \DateTime
    {
        return $this->dataMatricula;
    }

    public function isMesmoAlunoETurma(Aluno $aluno, Turma $turma): bool
    {
        return $this->aluno->getCpf() === $aluno->getCpf()
            && $this->turma->getNome() === $turma->getNome();
    }
}
