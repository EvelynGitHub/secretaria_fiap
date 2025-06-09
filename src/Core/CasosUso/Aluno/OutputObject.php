<?php

namespace SecretariaFiap\Core\CasosUso\Aluno;

use DateTime;

class OutputObject
{
    private string $uuid;
    private string $nome;
    private string $cpf;
    private string $email;
    private string $dataNascimento;

    private function __construct(array $values)
    {
        $this->uuid = $values['uuid'] ?? null;
        $this->nome = $values['nome'] ?? null;
        $this->cpf = $values['cpf'] ?? null;
        $this->email = $values['email'] ?? null;
        $this->dataNascimento = $values['data_nascimento'] ?? null;

    }

    public static function create(array $data): OutputObject
    {
        // $data['data_nascimento'] = $data['data_nascimento'];
        return new OutputObject($data);
    }

    public static function paginar(array $data): OutputObject
    {
        // $data['data_nascimento'] = new DateTime($data['data_nascimento']);
        return new OutputObject($data);
    }

    public function __get(string $name)
    {
        if (!property_exists($this, $name)) {
            throw new \Exception(sprintf("Não existe essa propriedade no Aluno: '%s'", $name));
        }

        return $this->{$name};
    }
}
