<?php

namespace SecretariaFiap\Core\Entidade;

use SecretariaFiap\Helpers\GeradorUuid;

class Turma
{
    private ?int $id = null;
    private string $uuid;
    private string $nome;
    private string $descricao;
    private int $qtdAlunos = 0;

    public function __construct(
        string $nome,
        string $descricao,
        ?string $uuid = null
    ) {
        $this->definirNome($nome);
        $this->descricao = trim($descricao);
        $this->uuid = $uuid ?? GeradorUuid::gerar();
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }


    public function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    private function definirNome(string $nome): void
    {
        if (strlen(trim($nome)) < 3) {
            throw new \InvalidArgumentException("Nome da turma deve ter no mínimo 3 caracteres.");
        }
        $this->nome = $nome;
    }

    public function getNome(): string
    {
        return $this->nome;
    }
    public function getDescricao(): string
    {
        return $this->descricao;
    }

    public function setQtdAlunos(int $qtdAlunos): void
    {
        $this->qtdAlunos = $qtdAlunos;
    }

    public function getQtdAlunos(): int
    {
        return $this->qtdAlunos;
    }
}
