<?php

declare(strict_types=1);

namespace SecretariaFiap\Core\Entidade;

class Admin
{
    private string $id;
    private string $uuid;
    private string $nome;
    private string $email;
    private Senha $senha;

    public function __construct(string $nome, string $email, Senha $senha)
    {
        $this->nome = $nome;
        $this->definirEmail($email);
        $this->senha = $senha;
    }

    private function definirEmail(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Email inválido.");
        }
        $this->email = $email;
    }

    public function getId(): string
    {
        return $this->id;
    }
    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function setSenha(Senha $senha): void
    {
        $this->senha = $senha;
    }

    public function getSenha(): Senha
    {
        return $this->senha;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getNome(): string
    {
        return $this->nome;
    }
}
