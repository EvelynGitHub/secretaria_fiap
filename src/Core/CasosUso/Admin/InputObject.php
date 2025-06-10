<?php

declare(strict_types=1);

namespace SecretariaFiap\Core\CasosUso\Admin;

class InputObject
{
    public string $email;
    public string $senha;

    public static function create(array $dados): self
    {
        $obj = new self();
        $obj->email = $dados['email'];
        $obj->senha = $dados['senha'];
        return $obj;
    }
}
