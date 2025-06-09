<?php

declare(strict_types=1);

namespace SecretariaFiap\Core\CasosUso\Matricula;

class InputObject
{
    public function __construct(
        public string $uuidAluno,
        public string $uuidTurma
    ) {
    }
}
