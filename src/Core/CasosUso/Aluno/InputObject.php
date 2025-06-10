<?php

namespace SecretariaFiap\Core\CasosUso\Aluno;

use DateTime;

class InputObject
{
    /**
     * uuid será usada como identificador para edição de Alunos
     * @var 
     */
    public readonly ?string $uuid;
    public readonly string $nome;
    public readonly string $cpf;
    public readonly string $email;
    public readonly ?string $senha;
    public readonly string $dataNascimento;

    private function __construct(array $values)
    {
        $this->uuid = $values['uuid'] ?? null;
        $this->nome = $values['nome'] ?? throw new \InvalidArgumentException("Nome é obrigatório.");
        $this->cpf = $values['cpf'] ?? throw new \InvalidArgumentException("CPF é obrigatório.");
        $this->email = $values['email'] ?? throw new \InvalidArgumentException("Email é obrigatório.");
        $this->dataNascimento = $values['data_nascimento'] ?? throw new \InvalidArgumentException("Data de Nascimento é obrigatória.");
        $this->senha = $values['senha'] ?? null;
    }

    public static function create(array $data): InputObject
    {
        return new InputObject($data);
    }

    public function __get(string $name)
    {
        if (!property_exists($this, $name)) {
            throw new \Exception(sprintf("Não existe essa propriedade no Aluno: '%s'", $name));
        }

        return $this->{$name};
    }
}