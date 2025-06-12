<?php

namespace SecretariaFiap\Core\CasosUso\Turma;

use SecretariaFiap\Helpers\OutputObjectInterface;

class OutputObject implements OutputObjectInterface
{
    public readonly string $uuid;
    public readonly string $nome;
    public readonly ?string $descricao;
    public readonly ?int $qtdAlunos;

    private function __construct(array $values)
    {
        $this->uuid = $values['uuid'] ?? null;
        $this->nome = $values['nome'] ?? null;
        $this->descricao = $values['descricao'] ?? null;
        $this->qtdAlunos = $values['qtd_alunos'] ?? 0;
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
            'descricao' => $this->descricao,
            'qtd_alunos' => $this->qtdAlunos
        ];
    }
}
