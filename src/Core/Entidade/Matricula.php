<?php

declare(strict_types=1);

namespace SecretariaFiap\Core\Entidade;

use SecretariaFiap\Helpers\GeradorUuid;

class Matricula
{
    private int $id;
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

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
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

}
