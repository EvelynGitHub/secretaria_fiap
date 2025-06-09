<?php

declare(strict_types=1);

namespace SecretariaFiap\Core\CasosUso\Matricula;

class OutputObject
{
    public function __construct(
        public bool $sucesso,
        public string $mensagem
    ) {
    }
}
