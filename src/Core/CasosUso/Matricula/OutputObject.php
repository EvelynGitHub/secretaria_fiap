<?php

declare(strict_types=1);

namespace SecretariaFiap\Core\CasosUso\Matricula;

class OutputObject
{
    public bool $sucesso;
    public string $mensagem;

    public function __construct(bool $sucesso, string $mensagem)
    {
        $this->sucesso = $sucesso;
        $this->mensagem = $mensagem;
    }
}
