<?php

namespace SecretariaFiap\Core\CasosUso\Aluno;

use DateTime;

class InputObject
{
    /**
     * uuid será usada como identificador para edição de Alunos
     * @var 
     */
    public ?string $uuid;
    public string $nome;
    public string $cpf;
    public string $email;
    public string $senha;
    public ?DateTime $dataNascimento;

    private function __construct(array $values)
    {
        $this->uuid = $values['uuid'] ?? null;
        $this->nome = $values['nome'] ?? '';
        $this->cpf = $values['cpf'] ?? '';
        $this->email = $values['email'] ?? '';
        $this->senha = $values['senha'] ?? '';
        $this->dataNascimento = $values['data_nascimento'] ?? null;
    }

    public static function create(array $data): InputObject
    {
        try {
            $data['data_nascimento'] = new DateTime($data['data_nascimento']);
            return new InputObject($data);
        } catch (\DateMalformedStringException $th) {
            throw new \Exception(sprintf("A data informada está em formato incorreto, use 'aaaa-mm-dd'"));
        }
    }

    public function __get(string $name)
    {
        if (!property_exists($this, $name)) {
            throw new \Exception(sprintf("Não existe essa propriedade no Aluno: '%s'", $name));
        }

        return $this->{$name};
    }
}