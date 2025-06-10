<?php

namespace SecretariaFiap\Core\CasosUso\Turma;

use DateTime;

class InputObject
{
    /**
     * uuid será usada como identificador para edição de Turmas
     * @var 
     */
    public readonly ?string $uuid;
    public readonly string $nome;
    public readonly ?string $descricao;

    private function __construct(array $values)
    {
        $this->uuid = $values['uuid'] ?? null;
        $this->nome = $values['nome'] ?? throw new \InvalidArgumentException("Nome é obrigatório.");
        $this->descricao = $values['descricao'] ?? null;
    }

    public static function create(array $data): InputObject
    {
        return new InputObject($data);
    }

}