<?php

declare(strict_types=1);

namespace SecretariaFiap\Core\Contratos\Repositorio;

use SecretariaFiap\Core\Entidade\Turma;

interface TurmaRepositorio
{
    public function buscarPorUuid(string $uuid): ?Turma;
}
