<?php

declare(strict_types=1);

namespace SecretariaFiap\Core\CasosUso\Admin;

class OutputObject
{
    public string $uuid;
    public string $nome;
    public string $token;

    public static function create(array $dados): self
    {
        $obj = new self();
        foreach ($dados as $chave => $valor) {
            $obj->$chave = $valor;
        }
        return $obj;
    }
}
