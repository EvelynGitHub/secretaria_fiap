<?php

declare(strict_types=1);

namespace SecretariaFiap\Core\Entidade;

// Senha é um Value Object 
class Senha
{
    private string $hash;

    private function __construct(string $hash)
    {
        $this->hash = $hash;
    }

    public static function criar(string $senhaPura): self
    {
        self::validar($senhaPura);
        return new self(password_hash($senhaPura, PASSWORD_DEFAULT));
    }

    public static function criarAPartirDoHash(string $hash): self
    {
        return new self($hash);
    }

    public static function validar(string $senha): void
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
    }

    public function verificar(string $senhaPura): bool
    {
        return password_verify($senhaPura, $this->hash);
    }

    public function getHash(): string
    {
        return $this->hash;
    }
}