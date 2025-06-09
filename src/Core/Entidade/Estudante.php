<?php

namespace SecretariaFiap\Core\Entidade;

use DateTime;

class Estudante
{
    private ?string $uuid;
    private string $nome;
    private string $cpf;
    private string $email;
    private string $senha;
    private ?DateTime $dataNascimento;


    public function setNome(string $nome): void
    {

    }


    public function getUuid(): string
    {
        return 'abc-123';
    }
}
