<?php

declare(strict_types=1);

namespace SecretariaFiap\Core\Entidade;

class Admin
{
    private string $nome;
    private string $email;
    private string $senhaHash;

    public function __construct(string $nome, string $email, string $senha)
    {
        $this->nome = $nome;
        $this->definirEmail($email);
        $this->definirSenha($senha);
    }

    private function definirEmail(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Email inválido.");
        }
        $this->email = $email;
    }

    private function definirSenha(string $senha): void
    {
        if (
            strlen($senha) < 8 ||
            !preg_match('/[A-Z]/', $senha) ||
            !preg_match('/[a-z]/', $senha) ||
            !preg_match('/[0-9]/', $senha) ||
            !preg_match('/[\W]/', $senha)
        ) {
            throw new \InvalidArgumentException("Senha fraca.");
        }
        $this->senhaHash = password_hash($senha, PASSWORD_DEFAULT);
    }

    public function verificarSenha(string $senha): bool
    {
        return password_verify($senha, $this->senhaHash);
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
