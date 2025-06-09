<?php

namespace SecretariaFiap\Core\CasosUso\Estudante;

use DateTime;

class InputObject
{
    /**
     * uuid será usada como identificador para edição de estudantes
     * @var 
     */
    private ?string $uuid;
    private string $nome;
    private string $cpf;
    private string $email;
    private string $senha;
    private ?DateTime $dataNascimento;

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
            throw new \Exception(sprintf("Não existe essa propriedade no Estudante: '%s'", $name));
        }

        return $this->{$name};
    }
}