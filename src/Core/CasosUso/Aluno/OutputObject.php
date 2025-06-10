<?php

namespace SecretariaFiap\Core\CasosUso\Aluno;

use SecretariaFiap\Helpers\OutputObjectInterface;

class OutputObject implements OutputObjectInterface
{
    public readonly string $uuid;
    public readonly string $nome;
    public readonly string $cpf;
    public readonly string $email;
    public readonly string $dataNascimento;

    private function __construct(array $values)
    {
        $this->uuid = $values['uuid'] ?? null;
        $this->nome = $values['nome'] ?? null;
        $this->cpf = $values['cpf'] ?? null;
        $this->email = $values['email'] ?? null;
        $this->dataNascimento = $values['data_nascimento'] ?? null;
    }

    public static function create(array $data): self
    {
        return new self($data);
    }

    public function toArray(): array
    {
        return [
            'uuid' => $this->uuid,
            'nome' => $this->nome,
            'cpf' => $this->cpf,
            'email' => $this->email,
            'data_nascimento' => $this->dataNascimento,
        ];
    }
}
