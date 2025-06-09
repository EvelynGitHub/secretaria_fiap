<?php

declare(strict_types=1);

namespace SecretariaFiap\Core\Entidade;

use SecretariaFiap\Helpers\GeradorUuid;

class Aluno
{
    private ?int $id = null;
    private string $uuid;
    private string $nome;
    private string $cpf;
    private string $email;
    private string $senhaHash;
    private ?\DateTime $dataNascimento;

    public function __construct(
        string $nome,
        string $cpf,
        string $email,
        string $senha,
        ?\DateTime $dataNascimento,
        ?string $uuid = null
    ) {
        $this->definirNome($nome);
        $this->validarCpf($cpf);
        $this->validarEmail($email);
        $this->validarSenha($senha);
        $this->cpf = $cpf;
        $this->email = $email;
        $this->senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        $this->dataNascimento = $dataNascimento;
        $this->uuid = $uuid ?? GeradorUuid::gerar();
    }

    private function definirNome(string $nome): void
    {
        if (strlen(trim($nome)) < 3) {
            throw new \InvalidArgumentException("Nome deve ter no mínimo 3 caracteres.");
        }
        $this->nome = $nome;
    }

    private function validarCpf(string $cpf): void
    {
        if (!preg_match('/^\d{11}$/', $cpf)) {
            throw new \InvalidArgumentException("CPF inválido.");
        }
    }

    private function validarEmail(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("E-mail inválido.");
        }
    }

    private function validarSenha(string $senha): void
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

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getCpf(): string
    {
        return $this->cpf;
    }

    public function getSenhaHash(): string
    {
        return $this->senhaHash;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getDataNascimento(): \DateTime
    {
        return $this->dataNascimento;
    }
}
