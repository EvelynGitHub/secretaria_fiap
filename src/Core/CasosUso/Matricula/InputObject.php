<?php

declare(strict_types=1);

namespace SecretariaFiap\Core\CasosUso\Matricula;

class InputObject
{
    public readonly string $uuidAluno;
    public readonly string $uuidTurma;

    private function __construct(array $values)
    {
        $this->uuidAluno = $values['uuid_aluno'] ?? throw new \InvalidArgumentException("Obrigatório informar o aluno.");
        ;
        $this->uuidTurma = $values['uuid_turma'] ?? throw new \InvalidArgumentException("Obrigatório informar a turma.");
    }

    public static function create(array $data): InputObject
    {
        return new InputObject($data);
    }
}
